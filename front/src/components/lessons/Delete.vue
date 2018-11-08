<template>
    <v-dialog
        v-model="dialog"
        max-width="320"
    >
        <v-card>
            <v-card-title class="headline">Remover aula?</v-card-title>
            <v-card-text>
                Tem certeza que deseja remover a aula {{aula.title}}?
            </v-card-text>            
            <v-card-actions>

                <v-spacer></v-spacer>

                <v-btn
                    flat
                    @click="dialog = false"
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
    data() {
        return {
            dialog: false,
            aula: [],
        }
    },
    created() {
        eventHub.$on('del-lesson', (aula) => {
            this.dialog = true;
            this.aula = aula;
        });
    },
    methods: {
        submit() {
            this.dialog = false;
            this.$store.dispatch('lessons/delete', this.aula.id);
        }
    }
}
</script>
