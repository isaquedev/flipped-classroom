<template>
    <v-layout row wrap>        
        <v-container grid-list-md> 
            <list-schoolclasses/>
        </v-container>
    </v-layout>
</template>

<script>
import ListSchoolClasses from './schoolclasses/List';

export default {
    components: {
        'list-schoolclasses': ListSchoolClasses,
    }
}
</script>

<style>

</style>

