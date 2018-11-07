<template>
    <div>
        <v-dialog v-model="dialog" max-width="400px">
                    <v-btn slot="activator" color="amber" dark class="mb-2 black--text">Novo Usuário</v-btn>
                    <v-card color="blue-grey lighten-4">
                        <v-card-title class="headline"> Adicionar Usuário </v-card-title>
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
                                <v-text-field
                                    v-model="data.password"
                                    :rules="validation.password"
                                    label="Password"
                                    required
                                ></v-text-field>
                                <v-autocomplete
                                    v-model="type"
                                    :items="users_types"
                                    label="Tipo de Usuário"
                                    no-data-text="Nenhum tipo encontrado cadastrado"
                                    :rules="validation.type"
                                    required
                                ></v-autocomplete>
                            </v-form>
                            <v-btn flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn flat :disabled="!valid" @click="submit()">Salvar</v-btn>
                        </v-container>
                    </v-card>
                </v-dialog>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            data: [],
            valid: false,
            type: null,
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
                    password: [
                        v => !!v || 'Senha é obrigatório'
                    ],
                    type: [
                        v => !!v || 'Tipo de usuário é obrigatório'
                    ],
            },
        }
    },
    methods: {
        submit() {            
            let dataOrganized = {
                'name': this.data.name,
                'login': this.data.login,
                'password': this.data.password,
                'type': this.users_types.indexOf(this.type) + 1,
            }
            this.dialog = false;
            this.$refs.form.reset();
            this.$store.dispatch('user/create', dataOrganized);
        },
    }
}
</script>
