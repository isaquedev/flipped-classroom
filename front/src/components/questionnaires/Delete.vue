<template>
    <v-dialog v-model="dialog" max-width="400">
        <v-card>
            <v-card-title class="headline">Remover questionário?</v-card-title>
            <v-card-text>
                Tem certeza que deseja remover o questionário {{title}}?<br>
                Remover o questionário remove também as questões!
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
                    @click="submit()"
                color="green darken-2"
                >Sim</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';

export default {
    created() {
        eventHub.$on('questionnaire-delete', (id, title) => {
            this.dialog = true;
            this.id = id;
            this.title = title;
        })
    },
    data() {
        return {
            dialog: false,
            id: null,
            title: null,
        }
    },
    methods: {
        submit() {
            this.dialog = false;
            this.$store.dispatch('questionnaires/delete', this.id);
        }
    }
}
</script>
