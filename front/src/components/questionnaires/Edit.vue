<template>
    <div>
        <v-dialog v-model="dialog" fullscreen>
            <v-btn
                fixed
                dark
                fab
                bottom
                right
                :color="isDisabled()"
                @click="submit()"
                mr-2
            ><v-icon>check</v-icon></v-btn>
            <v-card>
                <v-toolbar card color="amber" class="elevation-2">
                    <v-btn icon @click.native="onCancel()">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title> Editar Questionário </v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-layout column>
                    <v-container>
                        <v-form ref="form" v-model="valid">
                            <v-text-field
                                v-model="data.title"
                                :rules="validation.title"
                                label="Title"
                                required
                            ></v-text-field>
                            <v-expansion-panel>
                                <v-expansion-panel-content>
                                    <div slot="header" class="headline">Configurações</div>
                                    <v-card>
                                        <v-card-text>
                                        <v-switch
                                            v-model="data.is_public"
                                            label="Público"
                                        ></v-switch>
                                        <v-switch
                                            v-model="data.is_test"
                                            label="Prova"
                                        ></v-switch>
                                        <div pl-4 row wrap v-if="data.is_test">
                                            Duração
                                            <v-text-field
                                                shrink
                                                style="width: 60px"
                                                hint="Duração"
                                                v-model="data.duration"
                                                class="mt-0"
                                                single-line
                                                mask="time"
                                            ></v-text-field>
                                        </div>
                                        <v-switch
                                            v-model="data.random_answers"
                                            label="Randomizar Perguntas"
                                        ></v-switch>
                                        </v-card-text>
                                    </v-card>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <br>
                            <v-card>
                                <v-card-title class="headline">Questões</v-card-title>
                            </v-card>
                            <div>
                                <questions-list/>
                            </div>
                        </v-form>
                    </v-container>
                </v-layout>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import QuestionsList from './questions/List';
import { eventHub } from '../../eventHub';

export default {
    components: {
        'questions-list' : QuestionsList,
    },
    data() {
        return {
            dialog: false,
            data: [],
            hour: 0,
            min: 0,
            id: null,
            valid: false,
            type: null,
            validation: {
                    title: [
                        v => !!v || 'Título é obrigatório'
                    ],
            },
        }
    },
    methods: {
        submit() {
            if(this.isDisabled() == "amber") {
                this.$store.dispatch('questionnaires/update', [this.id, this.data]).then((res) => {
                    this.$store.dispatch('question/update', [this.id, this.$store.state.question.all]).then(() =>{
                        this.$store.commit('question/clean');
                    });
                })
                this.dialog = false;
                this.formReset();
            }
        },
        isDisabled(){
            return this.$store.state.question.all.length == 0 ? 
                "grey" : this.valid && this.$store.state.question.all.length > 0 ? "amber" : "grey";
        },
        onCancel() {
            this.dialog = false;
            this.formReset();
            this.$store.commit('question/clean');
        },
        formReset(){
            this.data = this.unMask(this.data, false)
            this.data = [];

        },
        unMask(questionnaire, unMask){
            questionnaire['is_public']        = this.toBoolen(questionnaire['is_public'], unMask);
            questionnaire['is_test']         = this.toBoolen(questionnaire['is_test'], unMask);
            questionnaire['random_answers']   = this.toBoolen(questionnaire['random_answers'], unMask);
            return questionnaire;
        },
        toBoolen(value, unMask) {
            if (unMask)
                return value == "sim" ? true : false;
            else
                return value == true ? "sim" : "não";
        }
    },
    created() {
        eventHub.$on('questionnaire-edit', (questionnaire) => {
            this.id = questionnaire.id;
            let tempQuestionnaire = {
                title: questionnaire.title,
                is_public: questionnaire.is_public,
                is_test: questionnaire.is_test,
                duration: questionnaire.duration,
                random_answers: questionnaire.random_answers,
                id_teacher: questionnaire.id_teacher
            };
            tempQuestionnaire = this.unMask(tempQuestionnaire, true);
            this.$store.dispatch('question/getAll', questionnaire.id);
            this.dialog = true;
            this.data = tempQuestionnaire;
        });
    }
}
</script>
