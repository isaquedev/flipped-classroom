<template>
        <v-dialog v-model="dialog" max-width="400px">
                    <v-card color="blue-grey lighten-4">
                        <v-card-title class="headline"> Editar Usuário </v-card-title>
                        <v-container>
                            <v-form ref="form" v-model="valid">
                                <v-text-field
                                    v-model="data.name"
                                    :rules="validation.name"
                                    label="Nome"
                                    required
                                ></v-text-field>
                                <v-text-field
                                    v-model="data.login"
                                    :rules="validation.login"
                                    label="Login"
                                    required
                                ></v-text-field>
                                <v-autocomplete
                                    v-model="user_type"
                                    :items="users_types"
                                    label="Tipo de Usuário"
                                    no-data-text="Nenhum tipo encontrado cadastrado"
                                    :rules="validation.user_type"
                                    required
                                ></v-autocomplete>
                            </v-form>
                        </v-container>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn flat :disabled="!valid" @click="submit()">Salvar</v-btn>
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
            data: [],
            valid: false,
            user_type: null,
            user_id: null,
            users_types: [
                'Professor',
                'Aluno',
            ],
            validation: {
                    name: [
                        v => !!v || 'Nome é obrigatório'
                    ],
                    login: [
                        v => !!v || 'Login é obrigatório'
                    ],
                    user_type: [
                        v => !!v || 'Tipo de usuário é obrigatório'
                    ],
            },
        }
    },
    created() {
        eventHub.$on('user-edit', (user) => {
            this.dialog = true;
            this.user_id = user.id;
            this.data.name = user.name;
            this.data.login = user.login;
            this.user_type = user.user_type;
        });
    },
    methods: {
        submit() {            
            let dataOrganized = {
                'name': this.data.name,
                'login': this.data.login,
                'user_type': this.users_types.indexOf(this.user_type) + 1,
            }
            this.dialog = false;
            this.$refs.form.reset();
            this.$store.dispatch('user/update', [this.user_id, dataOrganized]);
        },
    }
}
</script>
