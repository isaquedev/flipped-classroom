<template>
    <v-layout row wrap>
        <v-container grid-list-md v-if="current_user.type == 1"> 
            <list-questionnaires/>
        </v-container>
    </v-layout>
</template>

<script>
import ListQuestionnaires from './questionnaires/List';

export default {
    computed: {
        current_user() {
            return this.$store.state.auth.user;
        },
    },
    components: {
        'list-questionnaires': ListQuestionnaires,
    },
    created() {
        console.log('created');
    },
}
</script>
