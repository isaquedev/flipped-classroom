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

export default {
  name: 'App',
  computed: {
    year() {
      return (new Date()).getFullYear();
    }
  },
  mounted() {
        this.$store.dispatch('user/getUser').then((res) => {
          if (this.$store.state.user.user['user_type'] == 0) {
            this.getUsers();
          } 
        });        
        this.$store.dispatch('projects/getAll');
        this.$store.dispatch('user/getTeachers');
  },
  components: {
    'u-header' : Header
  },
  methods: {
    async getUsers() {
      await this.$store.dispatch('user/getUsers');
      await this.$store.dispatch('user/getUsersTurmas');
    }
  }
}
</script>
