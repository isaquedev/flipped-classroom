<template>
    <div>
        <v-flex xs12>
            <h2 class="display-1 mb-4">Turmas</h2>
        </v-flex>
        <v-layout row wrap>

        <v-flex md3 sm6 xs12 v-for="schoolclass in schoolclasses" :key="schoolclass.id">
            <v-card color="blue-grey darken-2" class="white--text">
                <v-card-title primary-title>
                    <div class="headline">{{ schoolclass.title }}</div>
                </v-card-title>
                <v-card-text v-if="teachers">
                    Professor(a): {{ getName(schoolclass) }}
                </v-card-text>
                <v-card-actions v-if="user != 0">
                    <v-btn flat dark :to="'/schoolclasses/' + schoolclass.id">Entrar</v-btn>                                     
                </v-card-actions>
                    <v-btn v-if="type == user" flat dark @click="edt_turma(schoolclass)">Editar</v-btn>
                    <v-btn v-if="type == user" flat dark @click="del_turma(schoolclass)">Remover</v-btn>
                    <v-btn v-if="type == user" flat dark @click="alunos_turma(schoolclass)">Alunos</v-btn>
            </v-card>
        </v-flex>
        <v-flex md3 sm6 xs12>
            <schoolclasses-edit/>
            <schoolclasses-delete/>
            <students-manager-list/>
            <schoolclasses-create v-if="type == user"></schoolclasses-create>
        </v-flex>
    </v-layout>
    </div>
</template>

<script>
    import SchoolClassCreate    from './Create';
    import SchoolClassDelete    from './Delete';
    import SchoolClassEdit      from './Edit';
    import StudentsManagerList from './students-manager/List'
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
            'schoolclasses-create'  : SchoolClassCreate,
            'schoolclasses-edit'    : SchoolClassEdit,
            'schoolclasses-delete'  : SchoolClassDelete,
            'students-manager-list' : StudentsManagerList,
        },
        methods: {
            getName(schoolclass) {
                let teacherName;
                for (let index = 0; index < this.teachers.length; index++) {
                    if(this.teachers[index]['id'] === schoolclass['id_teacher'])
                        teacherName = this.teachers[index]['name'];
                }
                return teacherName;
            },
            edt_turma(n) {
                eventHub.$emit('schoolclasses-edit', n);
            }, 
            del_turma(n) {
                eventHub.$emit('schoolclasses-delete', n);
            },
            alunos_turma(n) {
                eventHub.$emit('users-turmas-getted', n.id);
            }
        },

    }
</script>
