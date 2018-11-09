<template>
    <v-layout id="lessons-container" column>
        <v-flex class="text-xs-center"><v-progress-circular
                    v-if="isLoading"
                    :size="50"
                    color="amber"
                    indeterminate
                ></v-progress-circular></v-flex>
        <v-flex xs12 v-for="(aula, key) in aulas" :key="aula.id">
            <v-card color="blue-grey lighten-5">
                <v-card-title primary-title class="blue-grey white--text">
                    <div class="headline"> {{ aula.title }}</div>
                    <v-divider vertical class="mx-3"/>
                    {{formatData(aula.release_date)}}
                    <v-spacer></v-spacer>
                    <v-btn v-if="user.type == 1" color="amber" dark class="mb-2 black--text" @click="del(aula)">Remover</v-btn>
                    <v-btn v-if="user.type == 1" color="amber" dark class="mb-2 black--text" @click="edt(aula, key)">Editar</v-btn>
                    <div >
                        <v-btn flat icon color="white"
                            v-if="aula.text_content || aula.video"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="show(aula, key)"><v-icon >remove_red_eye</v-icon></v-btn>
                        <v-btn flat icon color="white"
                            v-if="!isDonedQuest(aula.id_questionnaire) && aula.id_questionnaire && !isLoadingQuest"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="openQuest(aula.id_questionnaire, false)"><v-icon>format_list_numbered</v-icon></v-btn>
                        <v-btn flat icon color="light-green "
                            v-if="isDonedQuest(aula.id_questionnaire) && aula.id_questionnaire && !isLoadingQuest"
                            :disabled="!(comparDate(aula.release_date) || user.type != 2)"
                            @click="openQuest(aula.id_questionnaire, true)"><v-icon>format_list_numbered</v-icon></v-btn>
                        <v-progress-circular
                            v-if="aula.id_questionnaire && isLoadingQuest"
                            :size="20"
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
        <lesson-delete/>
    </v-layout>
</template>

<script>
    import create from './Create';
    import show from './Show';
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
            }
        },
        components: {
            create,
            show,
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
            edt(aula, id){
                
            },
            del(aula){
                eventHub.$emit('del-lesson', aula);
            },
            openQuest(id, isDoned){
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
                    eventHub.$emit('questionnaire-show', id, false);
                }
                
            },
            formatData(date){
                return date.substring(8,10) + ' de ' + this.getMonth(date.substring(5,7))
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
