<template>
    <div>
        <v-dialog v-model="dialog" fullscreen>
            <v-btn slot="activator" color="amber" dark class="mb-2 black--text">Adicionar Questionário</v-btn>
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
                    <v-toolbar-title> Adicionar Questionário </v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-layout column>
                    <v-container>
                        <v-form ref="form" v-model="valid">
                            <v-text-field
                                v-model="data.title"
                                :rules="validation.title"
                                label="Título"
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
                                            v-model="data.random_answers"
                                            label="Aleatorizar Perguntas"
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
import QuestionsList from './questions/List'

export default {
    components: {
        'questions-list' : QuestionsList,
    },
    data() {
        return {
            dialog: false,
            data: {
                title: '',
                is_public: false,
                random_answers: false,
            },
            hour: 0,
            min: 0,
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
                this.$store.dispatch('questionnaires/create', this.data).then((res) => {
                    let id = this.$store.state.questionnaires.all[this.$store.state.questionnaires.all.length - 1]['id'];
                    this.$store.dispatch('question/createQuestion', [id, this.$store.state.question.all]).then(() =>{
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
            this.data = {
                title: '',
                is_public: false,
                random_answers: false,
            };
        }
    }
}
</script>
