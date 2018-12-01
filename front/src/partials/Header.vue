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
        <v-toolbar-title>
            Sistema de Gestão Educacional
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-title class="subheading mr-3">
            {{user.name}}
        </v-toolbar-title>
        <v-toolbar-items>
            <v-btn color="blue-grey white--text" @click="logout()">
                Sair<v-icon medium color="white" class="ml-2">exit_to_app</v-icon>
            </v-btn>
        </v-toolbar-items>
    </v-toolbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            drawer: false
        }
    }, 
    computed: {
        user() {
            return this.$store.state.auth.user;
        },
    },
    mounted() {
        console.log(window.innerWidth);
    },
    methods: {
        logout() {
            this.$store.dispatch('auth/logout')
        }
    },
    
}
</script>

