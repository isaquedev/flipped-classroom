<template>
    <v-dialog
        v-model="dialog"
        max-width="320"
    >
        <v-card>
            <v-card-title class="headline">Remover aluno?</v-card-title>
            <v-card-text>
                Tem certeza que deseja remover o aluno {{aluno.name}}?
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
import { eventHub } from '../../../eventHub';

export default {
    data() {
        return {
            dialog: false,
            aluno: [],
        }
    },
    created() {
        eventHub.$on('aluno-delete', (aluno) => {
            this.dialog = true;
            this.aluno = aluno;
        });
    },
    methods: {
        submit() {
            this.dialog = false;
            this.$store.dispatch('user/deleteUsersTurmas', [this.aluno.id, this.$route.params.id]);
        }
    }
}
</script>
