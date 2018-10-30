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
                    outline
                ></v-text-field>

                <v-menu
                    ref="dataPicker"
                    v-model="datap"
                    :close-on-content-click="false"
                    :return-value.sync="due_date"
                    required
                >
                    <v-text-field
                        slot="activator"
                        v-model="due_date"
                        label="Data de entrega"
                        readonly
                    ></v-text-field>
                    <v-date-picker
                        locale="pt-br"
                        v-model="due_date"
                        no-title
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
                        label="Hora da entrega"
                        readonly
                    ></v-text-field>
                    <v-time-picker
                        v-model="due_date_time"
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
export default {

  data() {
    return {
      data: {},
      valid: false,
      datap: false,
      timep: false,
      due_date: null,
      due_date_time: "12:00",
      validation: {
        title: [v => !!v || "Título é obrigatório"],        
      }
    };
  },
  methods: {
    submit() {
      this.data.turma_id = this.$route.params.id;
      this.$store.dispatch("sections/create", this.data).then(res => {
        this.$refs.form.reset();
      });
    },
  }, 
  mounted() {
    const date = new Date();
    this.due_date = date.toISOString().substr(0, 10);
  },
};
</script>
