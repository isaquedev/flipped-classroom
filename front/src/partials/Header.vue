<template>
    <div>
        <v-navigation-drawer app v-model="drawer" clipped>
        <v-list>
            <v-list-tile to="/schoolclasses">
                <v-list-tile-action>
                    <v-icon>class</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                    <v-list-tile-title>
                        Turmas
                    </v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>            

            <v-list-tile v-if="user.type == 1" to="/questionnaires">
                <v-list-tile-action>
                    <v-icon>format_list_numbered</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                    <v-list-tile-title>
                        Questionários
                    </v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>

            <v-list-tile v-if="user.type == 0" to="/users">
                <v-list-tile-action>
                    <v-icon>people</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                    <v-list-tile-title>
                        Usuários
                    </v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>

        </v-list>
    </v-navigation-drawer>

    <v-toolbar app clipped-left color="amber">
        <v-toolbar-side-icon @click.prevent="drawer = !drawer"></v-toolbar-side-icon>
        <v-toolbar-title>Sistema de Gestão Educacional</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
            <v-menu offset-y>
                <v-btn slot="activator" flat>{{user.name}}<v-icon>keyboard_arrow_down</v-icon></v-btn>                
                <v-list>
                    <v-list-tile min-height="100">
                        <v-btn flat>Perfil</v-btn>
                    </v-list-tile>
                    <v-list-tile>
                        <v-btn flat  @click="logout()">Sair</v-btn>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar-items>
    </v-toolbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            drawer: true
        }
    }, 
    computed: {              
        user() {
            return this.$store.state.auth.user;
        },
    },
    methods: {
        logout() {
            this.$store.dispatch('auth/logout')
        }
    },
    
}
</script>

