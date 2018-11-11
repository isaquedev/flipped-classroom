<template>
    <v-dialog
        v-model="dialog"
        fullscreen
    >
        <v-toolbar card color="amber" class="elevation-2">
            <v-btn icon @click.native="close()">
            <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title> {{title}} </v-toolbar-title>
            <v-divider vertical class="mx-3"/>
            <p class="title my-2" v-if="isDone && !isInResume">Nota: {{getNota()}}</p>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-card>
            <v-layout row>
                <v-flex :xs2="isInResume" v-if="isInResume">
                    <v-card>
                        <div class="headline elevation-1 blue-grey white--text text-xs-center pa-2">
                            Alunos
                        </div>
                    <div color="blue-grey lighten-5">
                        <div class="title py-3 text-xs-center">Concluiram</div>
                        <v-divider/>
                        <div class="text-xs-center">
                            <v-progress-circular
                                v-if="isLoadingStudents"
                                :size="30"
                                color="amber"
                                class="ma-3"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                        <v-flex v-for="(cStududent, key) in concludedStudents" :key="cStududent.id">
                            <div @click="openQuestById(key)" :class="selectedId == key ? 'grey lighten-4 pa-3' : 'pa-3'">
                                <div class="subheading">{{cStududent.name}}</div>
                                <div :class="(getNotaByStudent(key)) >= 7 ? 
                                                'green-text subheading mt-1 text-xs-center' :
                                                'red-text subheading mt-1 text-xs-center'">
                                    {{getNotaByStudent(key)}}/10
                                </div>
                            </div>
                            <v-divider/>
                        </v-flex>
                        <div class="title text-xs-center py-3" mt-2>Não concluiram</div>
                        <v-divider/>
                        <div class="text-xs-center">
                            <v-progress-circular
                                v-if="isLoadingStudents"
                                :size="30"
                                color="amber"
                                class="ma-4"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                        <v-flex class="pa-2"  v-for="nCStududent in notConcludedStudents" :key="nCStududent.id">
                            <div class="pa-3">
                                <div class="subheading">{{nCStududent.name}}</div>
                                <div class="grey--text text-xs-center subheading mt-1">
                                    -/10
                                </div>
                            </div>
                            <v-divider class="ma-2"/>
                        </v-flex>
                    </div>
                    </v-card>
                </v-flex>
                <v-divider vertical class="my-2"/>
                <v-flex :xs10="isInResume" :xs12="isStudent && !isInResume">
                    <v-container>
                        <div class="pt-1"></div>
                        <v-flex class="text-xs-center">
                            <v-progress-circular    
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
                                    <div v-html="question.enunciation" class="mb-3 title"></div>
                                    <div v-for="(answersObjs) in answersList[key]" :key="answersObjs.id">
                                        <v-radio-group v-for="(answer, a_key) in answersObjs" :key="answer.id" row v-model="isMarked[key]">
                                            <div :class="getColor(answer.id, true)">{{getLetter(a_key)}}</div>&nbsp;
                                            <v-radio style="display: inline-block;" :color="getColor(answer.id,false)" :readonly="isDone" :value="answer.id"></v-radio>
                                            <div v-html="answer.text">{{answer.text}}</div>
                                        </v-radio-group>
                                    </div>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-container>
                    <v-card-actions>
                        <v-btn
                            fixed dark fab bottom right :disabled="isInResume" :color="isDisabled()" @click="submit()" class="mr-2"
                        ><v-icon>check</v-icon></v-btn>
                    </v-card-actions>
                </v-flex>
            </v-layout>
        </v-card>
        <v-dialog v-model="dialogClose" max-width="360">
            <v-card>
                <v-card-title class="headline">Sair?</v-card-title>
                <v-card-text>
                    Tem certeza que deseja sair?<br>
                    O progresso do questionário será perdido.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        flat
                        @click="dialogClose = false"
                        color="red darken-2"
                    >Não</v-btn>
 
                    <v-btn
                        flat
                        @click="resetForm()"
                        color="green darken-2"
                    >Sim</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';
import _ from 'underscore';

export default {
    data() {
        return {
            dialog: false,
            title: null,
            id: null,
            questionnaire: [],
            questions: [],
            isLoading: true,
            isLoadingStudents: true,
            answersList: {},
            isMarked: [],
            isStudent: false,
            isDone: false,
            questionnaireResult: [],
            dialogClose: false,
            isInResume: false,
            selectedId: null,
            concludedStudents: [],
            notConcludedStudents: [],
            questionnairesResults: [],
        }
    },
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
    },
    created() {
        eventHub.$on('questionnaire-show', (id, isStudent, quest) => {
            this.dialog = true;
            this.id = id;
            this.isStudent = isStudent;
            this.getQuestionnaire(quest);
        }),
        eventHub.$on('questionnaire-dashboard', (id, lesson) => {
            this.dialog = true;
            this.id = id;
            this.isStudent = false;
            this.isInResume = true;
            this.$store.dispatch(
                'users_questionnaires/getUsersQuestionnairesByLesson', [this.$route.params.id, lesson.id]).then((res) => {
                    this.questionnairesResults = res.data;
                    this.$store.dispatch('user/getStudents').then((res) => {
                        let students = res.data;
                        this.concludedStudents = [];
                        this.notConcludedStudents = [];
                        students.forEach((student, s_key) => {
                            let isConcluded = false;
                            this.questionnairesResults.forEach((quest, q_key) => {
                                if (quest.id_student == student.id){
                                    this.concludedStudents.push(student);
                                    isConcluded = true;
                                }
                            })
                            if (!isConcluded){
                                this.notConcludedStudents.push(student);
                            }
                        })
                        this.isLoadingStudents = false;
                        if (this.concludedStudents.length > 0){
                            this.concludedStudents.sort(function(a,b){
                                if(a.name < a.name) {return -1;}
                                if(a.name > b.name) {return 1;}
                                return 0;
                            });
                            this.notConcludedStudents.sort(function(a,b){
                                if(a.name < a.name) {return -1;}
                                if(a.name > b.name) {return 1;}
                                return 0;
                            });

                            let ordenedQuestResults = [];

                            this.concludedStudents.forEach((student, s_key) => {
                                this.questionnairesResults.forEach((questR, key) => {
                                    if (questR.id_student == student.id){
                                        ordenedQuestResults[s_key] = questR;
                                    }
                                })
                            })

                            this.questionnairesResults = ordenedQuestResults;
                            this.getQuestionnaire(this.questionnairesResults[0]);
                            this.selectedId = 0;
                        } else {
                            this.isLoading = false;
                        }
                    })
                }
            );
        })
    },
    methods: {
        openQuestById(id) {
            if (this.selectedId != id){
                this.selectedId = id;
                this.questions = [];
                this.isLoading = true;
                this.getQuestionnaire(this.questionnairesResults[id]);
            }
        },
        getQuestionnaire(quest) {
            this.questionnaireResult = quest;
            this.$store.dispatch('questionnaires/show', this.id).then(() => {
                this.questionnaire = this.$store.state.questionnaires.single;
                this.title = this.questionnaire.title;
                this.$store.dispatch('question/getAll', this.questionnaire.id).then(() => {
                    this.isLoading = false;
                    this.setupQuestionnaire();
                }).catch(() => {
                    this.isLoading = false;
                });
            })
        },
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
            if (this.isStudent || this.isInResume){
                if(this.questionnaireResult != null){
                    this.isDone = true;
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
        getNotaByStudent(key){
            let markedAnswers = Object.values(this.questionnairesResults[key].student_answers_order);
            var result = _.countBy(markedAnswers, (markedAnswer) => {
                return markedAnswer == 0 ? 'hit' : 'miss';
            });
            
            result['hit'] = typeof result['hit'] == "undefined" ? 0 : result['hit'];
            result['miss'] = typeof result['miss'] == "undefined" ? 0 : result['miss'];

            return Number((result['hit'] / (result['hit'] + result['miss'])) * 10);
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
            if (this.isDone || !this.isStudent){
                this.resetForm();
            } else {
                this.dialogClose = true;
            }
        },
        submit(){
            if(this.isDisabled() == "amber"){
                if(this.isStudent){
                    this.submitQuest();
                }
                this.isDone = true;
            } else {
                if(this.isStudent){
                    
                }
            }
        },
        submitQuest(){
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

            for (let i = 0; i < this.questions.length; i++) {
                if(this.isMarked[i] == null){
                    this.isMarked[i] = 5;
                }
            }

            this.$store.dispatch('users_questionnaires/done', [this.questionnaire.id, this.$route.params.id, questions_order, answersOrder, this.isMarked])
        },
        resetForm(){
            this.isMarked = [];
            this.isDone = false;
            this.dialog = false;
            this.questionnaire = [];
            this.questions = [];
            this.isLoading = true;
            this.isLoadingStudents = true;
            this.studentsResults = [];
            this.concludedStudents = [];
            this.notConcludedStudents = [];
            if(this.isStudent){
                eventHub.$emit('questionnaire-done');
            }
        }
    },

}
</script>
