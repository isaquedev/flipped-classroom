<template>
    <v-card>
        <v-expansion-panel v-model="mergeQuestion" expand>
            <v-expansion-panel-content>
                <div slot="header" >Adicionar Questão</div>
                <v-card>
                    <v-card-text>
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
                    </v-card-text>
                    <v-card-actions>
                        <v-btn flat @click="cancelQuestion()">Cancelar</v-btn>
                        <v-btn flat :disabled="!isValid()" @click="submit()">Salvar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-card>
</template>


<script>
import { VueEditor } from 'vue2-editor'

export default {
    components: {
        VueEditor,
    },
    data() {
        return {
            data: [],
            enunciation: null,
            mergeQuestion: [],
            valid: false,
        }
    },
    methods: {
        cancelQuestion() {
            this.resetForm();
        },
        submit(){
            this.$store.dispatch('question/mergeQuestion', this.data).then(() => {
                this.resetForm();
            })
        },
        isValid(){
            return this.data.enunciation
                && this.data.correct_answer 
                && this.data.incorrect_answer1
                && this.data.incorrect_answer2
                && this.data.incorrect_answer3
                && this.data.incorrect_answer4;
        },
        resetForm(){
            this.mergeQuestion = [false];
            this.data.enunciation
                = this.data.correct_answer 
                = this.data.incorrect_answer1
                = this.data.incorrect_answer2
                = this.data.incorrect_answer3
                = this.data.incorrect_answer4
                = null;
        }
    },
}
</script>
