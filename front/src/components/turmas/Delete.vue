<template>
    <v-dialog
        v-model="dialog"
        max-width="320"
    >
        <v-card>
            <v-card-title class="headline">Remover turma?</v-card-title>
            <v-card-text>
                Tem certeza que deseja remover a turma {{turma.title}}?
            </v-card-text>
            <v-card-actions>

                <v-spacer></v-spacer>

                <v-btn
                    flat
                    @click="dialog = false"
                    color="red darken-2"
                >Cancelar</v-btn>
 
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
            turma: {},
        }
    },
    methods: {
            submit() {
                this.dialog = false;
                this.$store.dispatch('projects/delete', this.turma.id);
            },
            
        },

    created() {
        eventHub.$on('projects-delete', (turma) => {
            this.dialog = true;
            this.turma = turma;
        });
    },   
}
</script>
