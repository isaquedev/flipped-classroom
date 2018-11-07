<template>
  <v-app>
    <u-header/>
    <v-content>
      <v-container>
        <router-view/>
      </v-container>
    </v-content>
    <v-footer app>
      <span>&copy; <a href="https://unicarioca.edu.br">Unicarioca</a> - {{year}}</span>
    </v-footer>
  </v-app>
</template>

<script>
import Header from '../partials/Header';
import { eventHub } from '../eventHub';

export default {
  name: 'App',
  computed: {
    year() {
      return (new Date()).getFullYear();
    }
  },
  mounted() {
        this.$store.dispatch('auth/getUser').then((res) => {
          if (this.$store.state.auth.user['type'] == 0) {
            this.getAdminData();
          }
          if (this.$store.state.auth.user['type'] == 1) {
            this.$store.dispatch('questionnaires/getAll', this.$store.state.auth.user['id']);
          }
        });
        this.$store.dispatch('schoolclasses/getAll');
        this.$store.dispatch('user/getTeachers');
  },
  components: {
    'u-header' : Header
  },
  methods: {
    async getAdminData() {
      await this.$store.dispatch('user/getUsers');
      await this.$store.dispatch('user/getUsersTurmas').then(() => {
        eventHub.$emit('users-turmas-getted');
      });
    }
  }
}
</script>
