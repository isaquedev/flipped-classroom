<template>
    <div>
        <v-dialog v-if="!isCreatingUser" v-model="dialog" max-width="400px">
            <v-btn slot="activator" flat class="mb-2 black--text" @click="getOthersStudents()">Adicionar Aluno</v-btn>
                <v-card color="blue-grey lighten-4">
                    <v-card-title class="headline"> Adicionar Aluno</v-card-title>                    
                    <v-container>
                        <v-form ref="form" v-model="valid">
                            <v-autocomplete
                                v-model="student"
                               :items="students"
                                label="Aluno"
                                no-data-text="Nenhum aluno encontrado"
                                :rules="validation.student"
                                required
                            ></v-autocomplete>
                        </v-form>
                    <v-btn flat @click="dialog = false">Cancelar</v-btn>
                    <v-btn flat :disabled="!valid" @click="submit()">Salvar</v-btn>
                </v-container>
            </v-card>
        </v-dialog>
        <v-btn v-if="isCreatingUser" :disabled="true" flat class="mb-2 black--text">Adicionar Aluno</v-btn>
    </div>
</template>

<script>

import { eventHub } from '../../../eventHub';

export default {
    data() {
        return {
            dialog: false,
            data: [],
            valid: false,
            student: null,
            students: [],
            isCreatingUser: false,
            validation: {
                    student: [
                        v => !!v || 'Estudante é obrigatório'
                    ],
            },
        }
    },
    methods: {
        submit() {            
            let dataOrganized = {
                'id_student': this.student.split(" ")[0],
                'id_schoolclass': this.$route.params.id,
            }
            this.dialog = false;
            this.$refs.form.reset();
            this.isCreatingUser = true;
            this.$store.dispatch('user/createUserTurma', dataOrganized).then(() => {
                this.isCreatingUser = false;
            });
        },
        getOthersStudents() {
            this.students = this.$store.getters['user/byNotClassId'](this.$route.params.id);
        }
    },
}
</script>
