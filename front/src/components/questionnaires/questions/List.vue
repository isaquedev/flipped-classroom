<template>
    <v-layout column>
        <v-flex xs12 v-for="(question, key) in questions" :key="question.id">
            <v-card>
                <v-expansion-panel >
                    <v-expansion-panel-content>
                        <div slot="header" >
                            <v-icon @click="edtQuestion(question, key)">edit</v-icon>
                            <v-icon @click="delQuestion(key)">delete</v-icon>
                            <v-divider
                                class="mx-3"
                                vertical
                                light
                            ></v-divider>
                            Questão {{key + 1}} 
                        </div>
                        <v-card>
                            <v-card-text>{{question.enunciation}}</v-card-text>
                            <v-card-text>
                                <table>
                                    <tr>(A) {{question.correct_answer}}</tr>
                                    <tr>(B) {{question.incorrect_answer1}}</tr>
                                    <tr>(C) {{question.incorrect_answer2}}</tr>
                                    <tr>(D) {{question.incorrect_answer3}}</tr>
                                    <tr>(E) {{question.incorrect_answer4}}</tr>
                                </table>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-card>
        </v-flex>
        <v-flex xs12>
            <create-question/>
        </v-flex>
        <!--Delete Question-->
        <v-dialog v-model="dialogDel" max-width="320">
            <v-card>
                <v-card-title class="headline">Remover Questão?</v-card-title>
                <v-card-text>
                    Tem certeza que deseja remover a questão {{id}}?
                </v-card-text>            
                <v-card-actions>

                    <v-spacer></v-spacer>

                    <v-btn
                        flat
                        @click="dialogDel = false"
                        color="red darken-2"
                    >Não</v-btn>
 
                    <v-btn
                        flat
                        @click="submitDel()"
                        color="green darken-2"
                    >Sim</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Edit Question -->
        <v-dialog v-model="dialogEdt" max-width="600px">
            <v-card color="blue-grey lighten-4">
                <v-card-title class="headline"> Editar Questão </v-card-title>
                <v-container>
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
                </v-container>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="cancelEdt()">Cancelar</v-btn>
                    <v-btn flat :disabled="!valid" @click="submitEdt()">Salvar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import { eventHub } from '../../../eventHub';
import CreateQuestion from './Create';

export default {
    components: {
        'create-question': CreateQuestion,
    },
    data() {
        return {
            id: 0,
            dialogDel: false,
            data: [],
            dialogEdt: false,
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
    methods:{
        delQuestion(id){
            this.dialogDel = true;
            this.id = id;
        },
        edtQuestion(question, id){
            this.dialogEdt = true;
            this.id = id;
            this.data = {
                enunciation: question.enunciation,
                correct_answer: question.correct_answer,
                incorrect_answer1: question.incorrect_answer1,
                incorrect_answer2: question.incorrect_answer2,
                incorrect_answer3: question.incorrect_answer3,
                incorrect_answer4: question.incorrect_answer4,
            };
        },
        submitDel(){
            this.$store.commit('question/delQuestion', this.id);
            this.dialogDel = false;
        },
        submitEdt() {
            this.$store.commit('question/edtQuestion', [this.id, this.data]);
            this.dialogEdt = false;
        },
        cancelEdt() {
            this.dialogEdt = false;
            this.$refs.formQuestion.reset();
        },
    },
    computed: {
        questions() {
            return this.$store.state.question.all;
        }
    },
}
</script>
