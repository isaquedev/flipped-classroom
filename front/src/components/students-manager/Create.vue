<template>
    <div>
        <v-dialog v-model="dialog" max-width="400px">
            <v-btn slot="activator" color="amber" dark class="mb-2 black--text">Adicionar Estudante</v-btn>
                <v-card color="blue-grey lighten-4">
                    <v-card-title class="headline"> Adicionar Estudante ({{turma.turma_name}}) </v-card-title>                    
                    <v-container>
                        <v-form ref="form" v-model="valid">
                            <v-autocomplete
                                v-model="student"
                               :items="students"
                                label="Estudante"
                                no-data-text="Nenhum estudante encontrado"
                                :rules="validation.student"
                                required
                            ></v-autocomplete>
                        </v-form>
                    <v-btn flat @click="dialog = false">Cancelar</v-btn>
                    <v-btn flat :disabled="!valid" @click="submit()">Salvar</v-btn>
                </v-container>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { eventHub } from '../../eventHub';

export default {
    data() {
        return {
            turma: [],
            dialog: false,
            data: [],
            valid: false,
            student: null,
            students: [],
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
                'id_aluno': this.student.split(" ")[0],
                'id_turma': this.turma.id,
            }
            this.dialog = false;
            this.$refs.form.reset();
            console.log(dataOrganized);
            this.$store.dispatch('user/createUserTurma', dataOrganized);
        },
        getOthersStudents(index) {
            this.students = this.$store.getters['user/students'](index);
        }
    },
    created() {
        eventHub.$on('user-add', (turma, index) => {
            this.dialog = true;
            this.turma = turma;
            this.getOthersStudents(index);
        });
    },
}
</script>
