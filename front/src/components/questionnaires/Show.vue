<template>
    <v-dialog
        v-model="dialog"
        fullscreen
    >
        <v-card>
            <v-toolbar card color="amber" class="elevation-2">
                <v-btn icon @click.native="close()">
                <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title> {{questionnaire.title}} </v-toolbar-title>
                <v-divider vertical class="mx-3"/>
                <p class="title my-2" v-if="isDone">Nota: {{getNota()}}</p>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-container>
                <div class="pt-1"></div>
                <v-flex class="text-xs-center"><v-progress-circular    
                    v-if="isLoading"
                    :size="50"
                    color="amber"
                    indeterminate
                ></v-progress-circular></v-flex>
                <v-flex xs12 v-for="(question, key) in questions" :key="question.id">
                    <v-card color="blue-grey lighten-5 mb-2">
                        <v-card-title primary-title :class="getAcertou(key)">
                            <div> Questão {{key + 1}} </div>
                            <v-divider vertical class="mx-3"/>
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-container>
                            <div v-html="question.enunciation" class="mb-3"></div>
                            <div v-for="(answersObjs) in answersList[key]" :key="answersObjs.id">
                                <v-radio-group v-for="(answer, a_key) in answersObjs" :key="answer.id" row v-model="isMarked[key]">
                                    <div :class="getColor(answer.id, true)">{{getLetter(a_key)}}</div>&nbsp; <v-radio :color="getColor(answer.id,false)" :readonly="isDone" :value="answer.id"></v-radio> <div v-html="answer.text"></div>
                                </v-radio-group>
                            </div>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-container>
            <v-card-actions>
                <v-btn
                fixed
                dark
                fab
                bottom
                right
                :color="isDisabled()"
                @click="submit()"
                class="mr-2"
            ><v-icon>check</v-icon></v-btn>
            </v-card-actions>
            {{questionnaire}}
        </v-card>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';
import _ from 'underscore';

export default {
    data() {
        return {
            dialog: false,
            id: null,
            questionnaire: [],
            questions: [],
            isLoading: true,
            answersList: {},
            isMarked: [],
            isStudent: false,
            isDone: false,
            questionnaireResult: [],
        }
    },
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
        
    },
    created() {
        eventHub.$on('questionnaire-show', (id, isStudent, value) => {
            this.dialog = true;
            this.id = id;
            this.isStudent = isStudent;
            this.questionnaireResult = value;
            this.$store.dispatch('questionnaires/show', id).then(() => {
                this.questionnaire = this.$store.state.questionnaires.single;
                this.$store.dispatch('question/getAll', this.questionnaire.id).then(() => {
                    this.isLoading = false;
                    this.setupQuestionnaire();
                }).catch(() => {
                    this.isLoading = false;
                })
            })
        })
    },
    methods: {
        getLetter(key){
            let letters = 'ABCDE'.split("");
            return "(" + letters[key] + ")";
        },
        isDisabled(){
            let isDisabled = _.countBy(this.isMarked, (mark) => {
                return typeof mark == 'number';
            })
            return this.isDone ? "grey" : this.questions.length == 0 ? 
                "grey" : (this.isMarked.length == this.questions.length) && (this.isMarked.length == isDisabled['true']) ?
                    "amber" : "grey";
        },
        setupQuestionnaire() {
            if (this.questionnaire.random_answers == 1){
                this.$store.commit('question/randomQuestions');
            } 
            this.questions = this.$store.state.question.all;
            this.questions.forEach((question, key) => {
                this.answersList[key] = {
                    answers: [
                        {
                            id: 0,
                            text: question['correct_answer'],
                            isMarked: false,
                        },
                        {
                            id: 1,
                            text: question['incorrect_answer1'],
                            isMarked: false,
                        },
                        {
                            id: 2,
                            text: question['incorrect_answer2'],
                            isMarked: false,
                        },
                        {
                            id: 3,
                            text: question['incorrect_answer3'],
                            isMarked: false,
                        },
                        {
                            id: 4,
                            text: question['incorrect_answer4'],
                            isMarked: false,
                        },
                    ]
                }
                this.answersList[key].answers = _.shuffle(this.answersList[key].answers);
            })
            if (this.isStudent){
                if(this.questionnaireResult != null){
                    this.isDone = true;
                    console.log('isMarked');
                    console.log(this.isMarked);
                    console.log('questiquestionnaireResults');
                    console.log(this.questionnaireResult);
                    var updatedQuestionOrder = [];
                    this.questionnaireResult.questions_order.forEach((result, key) => {
                        this.questions.forEach((question, q_key) => {
                            if (question.id == result){
                                updatedQuestionOrder[key] = question;
                            }
                        })
                    });
                    this.questions = updatedQuestionOrder;
                    var updatedAnswersList = [];
                    this.questionnaireResult.answers_order.forEach((result, key) => {
                        updatedAnswersList[key] = []
                        result.forEach((id, i_key) => {
                            this.answersList[key].answers.forEach((answer, a_key) => {
                                if(answer.id == id){
                                    updatedAnswersList[key][i_key] = answer;
                                }
                            })
                        })
                    });
                    let tempAL = Object.values(this.answersList);
                    tempAL.forEach((answers, key) => {
                        this.answersList[key].answers = updatedAnswersList[key];
                    })
                    this.isMarked = this.questionnaireResult.student_answers_order.map(Number);
                }
            }
        },
        getNota(){
            let result = _.countBy(this.isMarked, (markedAnswer) => {
                return markedAnswer == 0 ? 'hit' : 'miss';
            })
            result['hit'] = typeof result['hit'] == "undefined" ? 0 : result['hit'];
            result['miss'] = typeof result['miss'] == "undefined" ? 0 : result['miss'];
            let nota = (result['hit'] / (result['hit'] + result['miss'])) * 10;
            return nota + "/10";
        },
        getColor(id, isText){
            if (isText)
                return this.isDone ? id == 0 ? 'green--text' : 'red--text' : '';
            return this.isDone ? id == 0 ? 'green' : 'red' : '';
        },
        getAcertou(id){
            let isAcertou = 
                this.isDone ? this.isMarked[id] == 0 ? 'green' : 'red' : 'blue-grey'; 
            return isAcertou + ' white--text headline';
        },
        close(){
            this.isMarked = [];
            this.isDone = false;
            this.dialog = false;
            this.questionnaire = [];
            this.questions = [];
            this.isLoading = true;
            if(this.isDone){
                eventHub.$emit('questionnaire-done');
            }
        },
        submit(){
            if(this.isDisabled() == "amber"){
                if(this.isStudent){
                    const questions_order = [];
                    this.questions.forEach((question, key) => {
                        questions_order[key] = question.id;
                    })
                    
                    const answersOrder = [];
                    const answersList = Object.values(this.answersList);
                    for (let x = 0; x < answersList.length; x++) {
                        answersOrder[x] = [];
                        for (let y = 0; y < answersList[x].answers.length; y++) {
                            answersOrder[x][y] = answersList[x].answers[y].id;
                        }
                    }

                    const studentAnswersOrder = Object.values(this.isMarked);
                    this.$store.dispatch('users_questionnaires/done', [this.questionnaire.id, this.$route.params.id, questions_order, answersOrder, studentAnswersOrder])
                }
                this.isDone = true;
            }
        },
    },

}
</script>
