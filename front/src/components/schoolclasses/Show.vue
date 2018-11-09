<template>
    <div>
        <v-layout mb-1>
            <v-flex xs12>
                <v-card color="white">
                    <v-card-title>
                        <h2 class="display-1">{{ turma.title }} </h2>
                        <v-divider vertical class="mx-3"/>
                        <h2> {{ teacher.name }} </h2>
                        <v-spacer></v-spacer>
                        <students-manager-list v-if="user.type == 0"/>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap>

        <lessons :turma="turma"/>

        <v-flex xs12>
            <v-btn to="/schoolclasses">Voltar</v-btn>
        </v-flex>

    </v-layout>
    </div>    
</template>

<script>
    import Lessons from '../lessons/List';
    import StudentsManagerList from './students-manager/List'

    export default {
        computed: {
            turma() {
                const id = this.$route.params.id;
                return this.$store.getters['schoolclasses/byId'](id);
            },
            teacher() {
                const id = this.turma['id_teacher'];
                return this.$store.getters['user/teacherById'](id);
            },
            user() {
                return this.$store.state.auth.user;
            }
        },
        components: {
            'lessons'      : Lessons,
            'students-manager-list' : StudentsManagerList,
        },
    }
</script>