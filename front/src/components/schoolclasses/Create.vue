<template>
    <v-card color="blue-grey lighten-4">
        <v-card-title>
            <div class="headline">Adicionar Turma</div>
        </v-card-title>
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
                        label="Professor(a)"
                        item-text="Professor(a)"
                        no-data-text="Nenhum professor(a) cadastrado"
                        :rules="validation.teacher"
                        required
                    ></v-autocomplete>
                    
                    <v-btn flat @click="submit" v-show="teacher">Salvar</v-btn>
                </div>    
            </v-form>
        </v-container>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                valid: false,
                teacher: null,
                data: {},
                validation: {
                    title: [
                        v => !!v || 'Título é obrigatório'
                    ],
                    
                    teacher: [
                        v => !!v || 'Professor(a) é obrigatório'
                    ],

                },                
            }
        },
        methods: {
            submit() {                
                this.data.id_teacher = this.teacher.split(" ")[0];
                this.$store.dispatch('schoolclasses/create', this.data).then((res) => {
                    let schoolclass = this.$store.state.schoolclasses.all;
                    this.$store.commit('user/addStudentsList', schoolclass[schoolclass.length - 1]['id']);
                    this.$refs.form.reset();
                });
            }
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
