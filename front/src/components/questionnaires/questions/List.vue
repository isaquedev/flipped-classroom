<template>
    <v-layout column>
        <v-flex class="text-xs-center">
            <v-progress-circular
                v-if="isLoading"
                :size="50"
                color="amber"
                indeterminate
                class="my-2"
            ></v-progress-circular>
        </v-flex>
        <v-flex xs12 v-for="(question, key) in questions" :key="question.id">
            <v-card>
                <v-expansion-panel >
                    <v-expansion-panel-content>
                        <div slot="header" >
                            <v-icon @click="updateQuestion(question, key)">edit</v-icon>
                            <v-icon @click="deleteQuestion(key)">delete</v-icon>
                            <v-divider
                                class="mx-3"
                                vertical
                                light
                            ></v-divider>
                            Questão {{key + 1}} 
                        </div>
                        <v-card class="pa-3">
                            <div v-html="question.enunciation"></div>
                            (A)&nbsp;<div style="display: inline-block" v-html="question.correct_answer"></div>
                            <br/>
                            (B)&nbsp;<div style="display: inline-block" v-html="question.incorrect_answer1"></div>
                            <br/>
                            (C)&nbsp;<div style="display: inline-block" v-html="question.incorrect_answer2"></div>
                            <br/>
                            (D)&nbsp;<div style="display: inline-block" v-html="question.incorrect_answer3"></div>
                            <br/>
                            (E)&nbsp;<div style="display: inline-block" v-html="question.incorrect_answer4"></div>
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
                        <div class="mb-2 subheading" >Enunciado:</div>

                            <vue-editor v-model="data.enunciation"
                            ></vue-editor>
                            
                            <div class="my-2 subheading" >Questão correta:</div>
                            <vue-editor v-model="data.correct_answer"
                            ></vue-editor>

                            <div class="my-2 subheading" >Questão incorreta:</div>
                            <vue-editor v-model="data.incorrect_answer1"
                            ></vue-editor>

                            <div class="my-2 subheading" >Questão incorreta:</div>
                            <vue-editor v-model="data.incorrect_answer2"
                            ></vue-editor>

                            <div class="my-2 subheading" >Questão incorreta:</div>
                            <vue-editor v-model="data.incorrect_answer3"
                            ></vue-editor>

                            <div class="my-2 subheading" >Questão incorreta:</div>
                            <vue-editor v-model="data.incorrect_answer4"
                            ></vue-editor>
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
import { VueEditor } from 'vue2-editor'

export default {
    components: {
        'create-question': CreateQuestion,
        VueEditor,
    },
    data() {
        return {
            id: 0,
            dialogDel: false,
            data: [],
            dialogEdt: false,
            valid: false,
            isLoading: true,
        }
    },
    methods:{
        deleteQuestion(id){
            this.dialogDel = true;
            this.id = id;
        },
        updateQuestion(question, id){
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
            this.$store.commit('question/deleteQuestion', this.id);
            this.dialogDel = false;
        },
        submitEdt() {
            this.$store.commit('question/updateQuestion', [this.id, this.data]);
            this.dialogEdt = false;
        },
        cancelEdt() {
            this.dialogEdt = false;
            this.$refs.formQuestion.reset();
        },
    },
    computed: {
        questions() {
            this.isLoading = false;
            return this.$store.state.question.all;
        }
    },
}
</script>
