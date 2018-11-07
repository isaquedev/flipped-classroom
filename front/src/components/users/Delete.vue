<template>
    <v-dialog
        v-model="dialog"
        max-width="320"
    >
        <v-card>
            <v-card-title class="headline">Remover usuário?</v-card-title>
            <v-card-text v-if="user.type == 'Professor'">
                Remover um professor exclui todas as turmas e aulas que ele está vinculado
            </v-card-text>
            <v-card-text>
                Tem certeza que deseja remover o usuário {{user.name}}?
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
            user: [],
        }
    },
    created() {
        eventHub.$on('user-delete', (user) => {
            this.dialog = true;
            this.user = user;
        });
    },
    methods: {
        submit() {
            this.dialog = false;
            this.$store.dispatch('user/delete', this.user.id).then(() => {
                this.$store.dispatch('schoolclasses/getAll');
                this.$store.commit('user/deleteStudentsList', this.user.id);
            });
        }
    }
}
</script>
