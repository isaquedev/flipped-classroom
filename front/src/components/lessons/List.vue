<template>
    <v-layout id="lessons-container" column>
        <v-flex xs12 v-for="(aula, key) in aulas" :key="aula.id">
            <v-card color="blue-grey lighten-5">
                <v-card-title primary-title class="blue-grey white--text">
                    <div class="headline"> {{ aula.title }}</div>
                    <v-spacer></v-spacer>
                    <v-btn color="amber" dark class="mb-2 black--text" @click="show(aula, key)">Entrar</v-btn>
                </v-card-title>
                <v-container v-html="aula.text_content">
                </v-container>
            </v-card>
        </v-flex>
        <v-flex xs3>
            <create v-if="type == user['type'] && turma.id_teacher == user['id']"/>
        </v-flex>
        <show/>
    </v-layout>
</template>

<script>
    import create from './Create';
    import show from './Show';
    import { eventHub } from '../../eventHub'

    export default {
        props: [
        'turma',
        ],
        computed: {              
            user() {
                return this.$store.state.auth.user;
            },
        },
        watch: {
            user: function (to, from) { //Caso entre direto na url da turma de fora do sistema
                if (this.user.type == 0) {
                } else {
                    this.getViewPermission();
                }
            },
            turma: function (to, from) {    //Quando troco de uma turma para outra via url
                if(to.id == null) {
                    window.location = '/';
                } else {
                    this.getingAulas = false;
                    this.getAulas();
                }
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
        }, 
        mounted() { //Quando entro normalmente na turma ou via url direta anteriormente estando em outra parte do sistema            
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
                        this.aulas = this.$store.state.lessons.all;
                    });
                }
                this.getingAulas = true;
            },
            show(aula, id){
                eventHub.$emit('show-lesson', id, aula)
            }
        }
    }
</script>
