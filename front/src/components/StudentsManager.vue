<template>
    <v-layout row wrap>
        <v-container grid-list-md v-if="current_user.user_type == 0"> 
            <list-students-manager/>
        </v-container>
    </v-layout>
</template>

<script>
import ListStudentsManager from './students-manager/List';

export default {
    computed: {        
        current_user() {            
            return this.$store.state.user.user;
        },
    },
    components: {
        'list-students-manager': ListStudentsManager,
    }
}
</script>
