<template>
    <v-layout id="lessons-container" column>
        <v-flex class="text-xs-center"><v-progress-circular
                    v-if="isLoading"
                    :size="50"
                    color="amber"
                    indeterminate
                    class="my-2"
                ></v-progress-circular></v-flex>
        <v-flex xs12 v-for="(aula, key) in aulas" :key="aula.id">
            <v-card color="blue-grey lighten-5">
                <v-card-title primary-title class="blue-grey white--text">
                    <div class="headline"> {{ aula.title }}</div>
                    <v-divider vertical class="mx-3"/>
                    {{formatData(aula.release_date)}}
                    <v-spacer></v-spacer>
                    <v-btn flat icon v-if="user.type == 1" color="white" class="mb-2 black--text" @click="del(aula)"><v-icon>delete</v-icon></v-btn>
                    <v-btn flat icon v-if="user.type == 1" color="white" class="mb-2 black--text" @click="edt(aula)"><v-icon>edit</v-icon></v-btn>
                    <div >

                        <v-btn flat icon color="white"
                            v-if="aula.text_content && !aula.video"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="show(aula, key)"><v-icon >remove_red_eye</v-icon></v-btn>
                        <v-btn flat icon color="white"
                            v-if="aula.video"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="show(aula, key)"><v-icon >videocam</v-icon></v-btn>
                        
                        
                        <v-btn flat icon color="white"
                            v-if="!isDonedQuest(aula.id_questionnaire) && aula.id_questionnaire && !isLoadingQuest"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="openQuest(aula.id_questionnaire, false, aula)"><v-icon>format_list_numbered</v-icon></v-btn>
                        <v-btn flat icon color="green lighten-2"
                            v-if="isDonedQuest(aula.id_questionnaire) && aula.id_questionnaire && !isLoadingQuest"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="openQuest(aula.id_questionnaire, true, aula)"><v-icon>format_list_numbered</v-icon></v-btn>
                        <v-progress-circular
                            v-if="aula.id_questionnaire && isLoadingQuest"
                            :size="30"
                            class="pr-5"
                            color="white"
                            indeterminate
                        ></v-progress-circular>
                    </div>
                </v-card-title>
                <v-container>
                    {{aula.description}}
                </v-container>
            </v-card>
        </v-flex>
        <v-flex xs3>
            <create v-if="type == user['type'] && turma.id_teacher == user['id']"/>
        </v-flex>
        <show/>
        <edit/>
        <lesson-delete/>
        <v-dialog
        v-model="questEmpty"
        max-width="360"
    >
        <v-card>
            <v-card-title class="headline">Aviso</v-card-title>
            <v-card-text>
                O questionário ainda não teve nenhuma resposta
            </v-card-text>
            <v-card-actions>

                <v-spacer></v-spacer>

                <v-btn
                    flat
                    @click="questEmpty = false"
                >Ok</v-btn>
 
            </v-card-actions>
        </v-card>
    </v-dialog>
    </v-layout>
</template>

<script>
    import create from './Create';
    import show from './Show';
    import edit from './Edit';
    import lDelete from './Delete';
    import { eventHub } from '../../eventHub';

    export default {
        props: [
        'turma',
        ],
        computed: {              
            user() {
                return this.$store.state.auth.user;
            },
        },
        data() {
            return {
                type: 1,
                getingAulas: false,
                havePermission: false,
                aulas: [],
                isLoading: true,
                isLoadingQuest: true,
                donedQuests: [],
                questEmpty: false,
            }
        },
        components: {
            create,
            show,
            edit,
            'lesson-delete': lDelete,
        },
        created() {
            eventHub.$on('questionnaire-done', () => {
                this.isLoadingQuest = true;
                this.$store.dispatch(
                    'users_questionnaires/getUsersQuestionnaires', this.$route.params.id).then((res) => {
                    this.isLoadingQuest = false;
                    this.donedQuests = res.data;
                });
            })
        },
        mounted() {
            if (this.user.length != 0) {
                this.getingAulas = false;
                if (this.user.type == 0) {
                    this.getAulas();
                } else {
                    this.getViewPermission();
                }
            }
        },
        beforeUpdate() {
            if (this.user.length != 0) {
                this.getingAulas = false;
                if (this.user.type == 0) {
                    this.getAulas();
                } else {
                    this.getViewPermission();
                }
            }
        },
        watch: {
            turma: function (to, from) {    //Quando troco de uma turma para outra via url
                if(to.id == null) {
                    window.location = '/';
                } else {
                    this.getingAulas = false;
                    this.getAulas();
                }
            },
        },
        methods: {
            getViewPermission() {
                if (!this.havePermission){
                    this.$store.dispatch('lessons/getClassViewPermission', this.$route.params.id)
                    .then((res) => {
                        if (res.data == 0) {
                            window.location = "/";
                        } else {
                            this.getAulas();
                            this.havePermission = true;
                        }
                    });
                }
            },
            isDonedQuest(id_quest){
                let isDoned = false;
                this.donedQuests.forEach((quest, key) => {
                    if (quest.id_questionnaire == id_quest){
                        isDoned = true;
                    }
                })
                return isDoned;
            },
            getAulas() {
                if (!this.getingAulas){
                    this.$store.dispatch('lessons/getAll', this.$route.params.id).then(() => {
                        this.$store.commit('lessons/sortLessons');
                        this.aulas = this.$store.state.lessons.all;
                        this.isLoading = false;
                        if(this.user.type == 2){
                            this.$store.dispatch(
                                'users_questionnaires/getUsersQuestionnaires', this.$route.params.id).then((res) => {
                                this.isLoadingQuest = false;
                                this.donedQuests = res.data;
                            });
                        } else {
                            this.isLoading = false;
                            this.isLoadingQuest = false;
                        }
                    }).catch(() => {
                        this.isLoading = false;
                        this.isLoadingQuest = false;
                    })
                }
                this.getingAulas = true;
            },
            show(aula, id){
                eventHub.$emit('show-lesson', id, aula);
            },
            edt(aula){
                eventHub.$emit('edit-lesson', aula)
            },
            del(aula){
                eventHub.$emit('del-lesson', aula);
            },
            openQuest(id, isDoned, aula){
                if (this.user.type == 2){
                    if(isDoned){
                        let questResult =   this.donedQuests.find((quest) => {
                                                return quest.id_questionnaire == id;
                                            })
                        eventHub.$emit('questionnaire-show', id, true, questResult);
                    } else {
                        eventHub.$emit('questionnaire-show', id, true);
                    }
                } else {
                    this.isLoadingQuest = true;
                    this.$store.dispatch('users_questionnaires/getUsersQuestionnairesByLesson',
                        [this.$route.params.id, aula.id]).then((res) => {
                            this.isLoadingQuest = false;
                            if (res.data.length > 0) {
                                eventHub.$emit('questionnaire-dashboard', id, aula);
                            } else {
                                this.questEmpty = true;
                            }
                            
                        })
                }
                
            },
            formatData(date){
                let tempDate = new Date(date);
                /*let timeDiff = Math.abs(Date.now() - tempDate.getTime());
                let daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                console.log(daysDiff);*/
                return tempDate.getDate().toLocaleString() + ' de ' + this.getMonth(tempDate.getMonth().toLocaleString())
                    + ' (' + date.substring(11,16) + ')';
            },
            getMonth(month){
                return ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"][month - 1];
            },
            comparDate(date){
                const actualDate = new Date();
                let requestDateTime = new Date(date);
                if (requestDateTime < actualDate){
                    return true;
                } 
                return false;
            }
        }
    }
</script>
