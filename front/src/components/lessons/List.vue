<template>
    <v-layout id="lessons-container" column>
        <v-flex xs12 v-for="(aula, key) in aulas" :key="aula.id">
            <v-card color="blue-grey lighten-5">
                <v-card-title primary-title class="blue-grey white--text">
                    <div class="headline"> {{ aula.title }}</div>
                    <v-divider vertical class="mx-3"/>
                    {{formatData(aula.release_date)}}
                    <v-spacer></v-spacer>
                    <v-btn v-if="user.type == 1" color="amber" dark class="mb-2 black--text" @click="del(aula)">Remover</v-btn>
                    <v-btn v-if="user.type == 1" color="amber" dark class="mb-2 black--text" @click="edt(aula, key)">Editar</v-btn>
                    <v-btn v-if="comparDate(aula.release_date) || user.type != 2" color="amber" dark class="mb-2 black--text" @click="show(aula, key)">Entrar</v-btn>
                </v-card-title>
                <v-container>
                    <v-icon v-if="aula.text_content">description</v-icon>
                    <v-icon v-if="aula.video">videocam</v-icon>
                    <v-icon v-if="aula.id_questionnaire">format_list_numbered</v-icon>
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
                aulas: [],
            }
        },
        components: {
            create,
            show,
            'lesson-delete': lDelete,
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
        methods: {
            getViewPermission() {
                this.$store.dispatch('lessons/getClassViewPermission', this.$route.params.id)
                    .then((res) => {
                        if (res.data == 0) {
                            window.location = "/";
                        } else {
                            this.getAulas();
                        }
                    }
                )
            },
            getAulas() {
                if (!this.getingAulas){
                    this.$store.dispatch('lessons/getAll', this.$route.params.id).then(() => {
                        this.$store.commit('lessons/sortLessons');
                        this.aulas = this.$store.state.lessons.all;
                    });
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
