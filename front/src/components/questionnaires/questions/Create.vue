<template>
    <v-card>
        <v-expansion-panel v-model="mergeQuestion" expand>
            <v-expansion-panel-content>
                <div slot="header" >Adicionar Questão</div>
                <v-card>
                    <v-card-text>
                        <v-form ref="formQuestion" v-model="valid">
                            <v-textarea
                                v-model="data.enunciation"
                                label="Enunciado"
                                required
                                :rules="validation.enunciation"
                            ></v-textarea>
                            <v-textarea
                                v-model="data.correct_answer"
                                label="Resposta Certa"
                                required
                                :rules="validation.correct_answer"
                            ></v-textarea>
                            <v-textarea
                                v-model="data.incorrect_answer1"
                                label="Resposta Errada 1"
                                required
                                :rules="validation.incorrect_answer1"
                            ></v-textarea>
                            <v-textarea
                                v-model="data.incorrect_answer2"
                                label="Resposta Errada 2"
                                required
                                :rules="validation.incorrect_answer2"
                            ></v-textarea>
                            <v-textarea
                                v-model="data.incorrect_answer3"
                                label="Resposta Errada 3"
                                required
                                :rules="validation.incorrect_answer3"
                            ></v-textarea>
                            <v-textarea
                                v-model="data.incorrect_answer4"
                                label="Resposta Errada 4"
                                required
                                :rules="validation.incorrect_answer4"
                            ></v-textarea>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn flat @click="cancelQuestion()">Cancelar</v-btn>
                        <v-btn flat :disabled="!valid" @click="submit()">Salvar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-card>
</template>


<script>
export default {
    data() {
        return {
            data: [],
            enunciation: null,
            mergeQuestion: [],
            valid: false,
            validation: {
                    enunciation: [
                        v => !!v || 'Enunciado é obrigatório'
                    ],
                    correct_answer: [
                        v => !!v || 'Questão correta é obrigatório'
                    ],
                    incorrect_answer1: [
                        v => !!v || 'Questão incorreta é obrigatório'
                    ],
                    incorrect_answer2: [
                        v => !!v || 'Questão incorreta é obrigatório'
                    ],
                    incorrect_answer3: [
                        v => !!v || 'Questão incorreta é obrigatório'
                    ],
                    incorrect_answer4: [
                        v => !!v || 'Questão incorreta é obrigatório'
                    ],
            },
        }
    },
    methods: {
        cancelQuestion() {
            this.mergeQuestion = [false];
            this.$refs.form.reset();
        },
        submit(){
            this.$store.dispatch('question/mergeQuestion', this.data).then((res) => {
                this.mergeQuestion = [false];
                this.$refs.formQuestion.reset();
            });
        }
    },
}
</script>
