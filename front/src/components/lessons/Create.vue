<template>
    <v-card color="blue-grey lighten-5">
        <v-card-title primary-title>
            <div class="headline">
                Adicionar aula
            </div>
        </v-card-title>
        <v-card-text>
            <v-form v-model="valid" ref="form">
                <v-text-field
                    v-model="data.title"
                    label="Título"
                    :rules="validation.title"
                    required
                ></v-text-field>

                <v-text-field
                    v-model="data.description"
                    label="Descrição"
                    :rules="validation.description"
                    required
                ></v-text-field>

                <vue-editor v-model="data.text_content"></vue-editor>

                <v-text-field
                    v-model="data.video"
                    label="Link do vídeo"
                ></v-text-field>

                <v-autocomplete
                        v-model="questionnaire"
                        :items="questionnaires"
                        label="Questionário"
                        item-text="Questionário"
                        no-data-text="Nenhum questionário encontrado"
                    ></v-autocomplete>

                <v-menu
                    ref="dataPicker"
                    v-model="datap"
                    :close-on-content-click="false"
                    :return-value.sync="due_date"
                >
                    <v-text-field
                        slot="activator"
                        v-model="due_date"
                        label="Data de liberação"
                        :rules="validation.date"
                        required
                        readonly
                    ></v-text-field>
                    <v-date-picker
                        locale="pt-br"
                        v-model="due_date"
                        scroollable
                    >
                        <v-btn flat color="secondary" @click="datap = false">Cancelar</v-btn>
                        <v-btn flat color="primary" @click="$refs.dataPicker.save(due_date)">Ok</v-btn>
                    </v-date-picker>
                </v-menu>
                    
                <v-menu
                    ref="timePicker"
                    v-model="timep"
                    :close-on-content-click="false"
                    :return-value.sync="due_date_time"
                    required
                >
                    <v-text-field
                        slot="activator"
                        v-model="due_date_time"
                        label="Hora da liberação"
                        :rules="validation.date_time"
                        required
                        readonly
                    ></v-text-field>
                    <v-time-picker
                        v-model="due_date_time"
                        format="24hr"
                        scrollable
                    >
                        <v-btn flat color="secondary" @click="timep = false">Cancelar</v-btn>
                        <v-btn flat color="primary" @click="$refs.timePicker.save(due_date_time)">Ok</v-btn>
                    </v-time-picker>
                </v-menu>

                <v-btn
                    :disabled="!valid"
                    @click="submit"
                >Salvar</v-btn>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
import { VueEditor } from 'vue2-editor'

export default {

    components: {
        VueEditor
    },
    computed: {
        questionnaires() {
            return this.$store.getters['questionnaires/filter'](this.$store.getters['lessons/getQuestionnairesInClass']());
        }
    },
    data() {
        return {
            data: {},
            valid: false,
            datap: false,
            timep: false,
            content: '',
            due_date: null,
            due_date_time: null,
            questionnaire: null,
            validation: {
                title: [v => !!v || "Título é obrigatório"],
                description: [v => !!v || "Descrição é obrigatório"],
                date:  [v => !!v || "Data é obrigatório"],
                date_time:  [v => !!v || "Hora é obrigatório"],
        }
    };
  },
  methods: {
    submit() {
      this.data.id_schoolclass = this.$route.params.id;
      this.data.release_date = this.due_date + " " + this.due_date_time;
      if (this.questionnaire != null){
          this.data.id_questionnaire = this.questionnaire.split(" ")[0];
      }
      this.$store.dispatch("lessons/create", this.data).then(res => {
        this.data.text_content = "";
        this.$refs.form.reset();
        this.$store.commit('lessons/sortLessons');
      });
    },
    resetDate(){
        const date = new Date();
        date.setDate(date.getDate());
        this.due_date = date.toISOString().substr(0, 10);
        date.setHours(date.getHours() - 2);
        this.due_date_time = date.toISOString().substring(11,16);
    }
  },
  mounted() {
      this.resetDate();
  },
};
</script>
