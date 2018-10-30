<template>
    <v-app>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Login</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-card-text class="red--text" v-model="error" v-if="error">
                                    Usuário ou senha incorretos
                                </v-card-text>
                                <v-form v-model="valid" ref="form">
                                    <v-text-field
                                        v-model="data.login"
                                        prepend-icon="person"                                        
                                        label="Email"
                                        :rules="validation.login"
                                        required
                                    ></v-text-field>
                                    <v-text-field
                                        v-model="data.password"
                                        prepend-icon="lock"
                                        label="Senha"
                                        type="password"
                                        :rules="validation.password"
                                        required
                                    ></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    :disabled="!valid"
                                    @click="submit()"
                                    color="primary"
                                >Entrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            error: false,
            valid: false,
            data: {},
            validation: {
                login: [
                    v => !!v || 'Login é obrigatório',
                ],
                password: [
                    v => !!v || 'Senha é obrigatório',
                ],
            }
        }
    }, 
    methods: {
        submit() {
            this.$store.dispatch('auth/login', this.data).catch(() => {
                this.error = true;
                this.$refs.form.reset();
            });
        }
    }
}
</script>
