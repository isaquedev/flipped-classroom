<template>
    <v-dialog
        v-model="dialog"
        max-width="320"
    >
    <v-card color="blue-grey lighten-4">
    <v-card-title class="headline">Editar Turma</v-card-title>
    <v-container>
        <v-form ref="form" v-model="valid" xs12>
            <v-text-field
                id="project-title"
                v-model="data.title"
                :rules="validation.title"
                label="Título"
                required
            ></v-text-field>
            <div v-show="data.title">

                 <v-autocomplete
                    v-model="teacher"
                    :items="teachers"
                    label="Professor"
                    item-text="Professor"
                    no-data-text="Nenhum professor cadastrado"
                    :rules="validation.teacher"
                    required
                ></v-autocomplete>                
            </div>    
        </v-form>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                flat
                @click="dialog = false"
                color="red darken-2"
            >Cancelar</v-btn>

            <v-btn
                :disabled="!data.title"
                flat
                @click="submit()"
                color="green darken-2"
            >Salvar</v-btn>
        </v-card-actions>
        </v-container>
    </v-card>                
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';

export default {
    data() {
        return  {
            valid: false,
            dialog: false,
            teacher: null,
            turma_id: null,
            data: [],
            validation: {
                    title: [
                        v => !!v || 'Título é obrigatório'
                    ],
            }
        }
    },
    methods: {
            submit() {
                this.dialog = false;
                this.data.id_professor = this.teacher.split(" ")[0];
                this.$store.dispatch('projects/update', [this.turma_id, this.data]).then((res) => {
                    console.log(res);
                });
            },
            
        },

    created() {
        eventHub.$on('projects-edit', (turma) => {
            this.dialog = true;
            this.turma_id = turma.id;
            this.data.title = turma.title;
            for (let i = 0; i < this.teachers.length; i++) {
                if(turma.id_professor == this.teachers[i].split(" ")[0]){
                    this.teacher = this.teachers[i];
                }
            }
        });
    },   
    computed: {
        teachers() {
            let response = this.$store.state.user.teachers;
            let result = [];
            for (let index = 0; index < response.length; index++) {
                result[index] = response[index]['id'] + ' - ' + response[index]['name'];
             
            }
            return result;
        }
    },
}
</script>
