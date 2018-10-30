<template>
    <v-layout id="sections-container" column>
        <v-flex xs12 v-for="aula in aulas" :key="aula.id">
            <v-card color="blue-grey lighten-5">
                <v-card-title primary-title class="blue-grey white--text">
                    <div class="headline"> {{ aula.title }}</div>
                </v-card-title>
                <v-card-text>
                    <tasks :section="aula.id"/>
                </v-card-text>
                <v-card-text>
                    <create-task :section="aula.id"/>
                </v-card-text>
            </v-card>
        </v-flex>
        <v-flex xs3>
            <create v-if="user['user_type'] == 0 || (user_type == user['user_type'] && turma.id_professor == user['id'])"/>
        </v-flex>
    </v-layout>
</template>

<script>
    import create from './Create';
    import tasks from '../tasks/List';
    import tasksCreate from '../tasks/Create'

    export default {
        props: [
        'turma',
        ],
        computed: {              
            user() {
                return this.$store.state.user.user;
            },
        },
        watch: {
            user: function (to, from) { //Caso entre direto na url da turma de fora do sistema                
                if (this.user.user_type == 0) {
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
                user_type: 1,
                getingAulas: false,
                aulas: [],
            }
        },

        components: {
            create,
            tasks,
            'create-task' : tasksCreate
        }, 
        mounted() { //Quando entro normalmente na turma ou via url direta anteriormente estando em outra parte do sistema            
            if (this.user.length != 0) {
                this.getingAulas = false;
                if (this.user.user_type == 0) {
                    this.getAulas();
                } else {
                    this.getViewPermission();
                }
            }
            
        },
        methods: {
            getViewPermission() {
                this.$store.dispatch('sections/getClassViewPermission', this.$route.params.id)
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
                    this.$store.dispatch('sections/getAll', this.$route.params.id).then(() => {
                        this.aulas = this.$store.state.sections.all;
                    });
                }
                this.getingAulas = true;
            }
        }
    }
</script>
