<template>
    <v-layout row wrap>
        <v-container grid-list-md v-if="current_user.type == 0"> 
            <list-users/>
        </v-container>
    </v-layout>
</template>

<script>
import ListUsers from './users/List';

export default {
    computed: {        
        current_user() {            
            return this.$store.state.auth.user;
        },
    },
    components: {
        'list-users': ListUsers,
    }
}
</script>
