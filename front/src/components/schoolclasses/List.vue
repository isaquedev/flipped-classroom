<template>
    <div>
        <v-flex xs12>
            <h2 class="display-1 mb-4">Turmas</h2>
        </v-flex>
        <v-layout row wrap>
        <v-flex xs3 v-for="project in schoolclasses" :key="project.id">
            <v-card color="blue-grey darken-2" class="white--text">
                <v-card-title primary-title>
                    <div class="headline">{{ project.title }}</div>
                </v-card-title>
                <v-card-text v-if="teachers">
                    Professor(a): {{ getName(project) }}
                </v-card-text>
                <v-card-actions>
                    <v-btn flat dark :to="'/schoolclasses/' + project.id">Entrar</v-btn>                                     
                </v-card-actions>
                <v-card-actions v-if="type == user">
                    <v-btn flat dark @click="edt_turma(project)">Editar</v-btn>
                    <v-btn flat dark @click="del_turma(project)">Remover</v-btn>
                </v-card-actions>   
            </v-card>
        </v-flex>
        <v-flex xs3>
            <schoolclasses-edit/>
            <schoolclasses-delete/>
            <schoolclasses-create v-if="type == user"></schoolclasses-create>
        </v-flex>
    </v-layout>
    </div>
</template>

<script>
    import ProjectCreate    from './Create';
    import ProjectDelete    from './Delete';
    import ProjectEdit      from './Edit';
    import { eventHub } from '../../eventHub';

    export default {
        data() {
            return {
                type: 0,
                del_dialog: false,
            }            
        },
        computed: {
            schoolclasses() {
                return this.$store.state.schoolclasses.all;
            },
            teachers() {                
                return this.$store.state.user.teachers;
            },
            user() {
                return this.$store.state.auth.user['type'];
            }
        },
        components: {
            'schoolclasses-create': ProjectCreate,
            'schoolclasses-edit': ProjectEdit,
            'schoolclasses-delete': ProjectDelete,
        },
        methods: {
            getName(project) {
                let teacherName;
                for (let index = 0; index < this.teachers.length; index++) {
                    if(this.teachers[index]['id'] === project['id_teacher'])
                        teacherName = this.teachers[index]['name'];
                }
                return teacherName;
            },
            edt_turma(n) {
                eventHub.$emit('schoolclasses-edit', n);
            }, 
            del_turma(n) {
                eventHub.$emit('schoolclasses-delete', n);
            }
        },

    }
</script>
