<template>
    <v-layout column>
        <v-toolbar class="elevation-1" flat color="white">
            <v-toolbar-title>Questionários</v-toolbar-title>
                <v-divider
                    class="mx-2"
                    inset
                    vertical
                ></v-divider>
                        
                <v-spacer></v-spacer>

                <create-questionnaire/>

                </v-toolbar>
                    <v-data-table
                        :headers="headers"
                        :items="questionnaires"
                        :loading="isLoading"
                        :pagination.sync="pagination"
                        :total-items="totalUsers"
                        no-data-text="Nenhum questionário encontrado"
                        class="elevation-1"
                    >
                    <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                    <template slot="items" slot-scope="props">
                        <td> {{props.item.header}}</td>
                        <td >{{props.item.id}}</td>
                        <td >{{props.item.title}}</td>
                        <td >{{props.item.id_teacher}}</td>
                        <td >{{props.item.is_public}}</td>  
                        <td >{{props.item.is_test}}</td>
                        <td class="text-xs-center">{{props.item.random_answers}}</td>
                    <td class="text-xs-left">
                    <v-icon
                        small
                        @click="editQuest(props.item)"
                    >
                        edit
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteQuest(props.item.id)"
                    > 
                        delete
                    </v-icon>
                    <v-icon
                        small
                        @click="showQuest(props.item.id)"
                    > 
                        remove_red_eye
                    </v-icon>
                </td>
            </template>
        </v-data-table>

        <delete-questionnaire/>
        <edit-questionnaire/>
        <show-questionnaire/>

    </v-layout>
</template>

<script>
import { eventHub }         from '../../eventHub';
import CreateQuestionnaire  from './Create';
import EditQuestionnaire    from './Edit';
import DeleteQuestionnaire  from './Delete';
import ShowQuestionnaire    from './Show';

export default {
    components: {
        'create-questionnaire' : CreateQuestionnaire,
        'delete-questionnaire' : DeleteQuestionnaire,
        'edit-questionnaire': EditQuestionnaire,
        'show-questionnaire': ShowQuestionnaire,
    },
    data() {
        return {
            isLoading: true,
            pagination: {
                page: 1,
                rowsPerPage: 10,
            },
            totalUsers: 0,
            headers: [
                {
                    align: 'left',
                    sortable: false,
                    value: 'header'
                },
                {text: 'ID', value: 'id'},
                {text: 'Title', value: 'title'},
                {text: 'Criador', value: 'id_teacher'},
                {text: 'Público', value: 'public'},
                {text: 'Prova', value: 'prova'},
                {text: 'Sortear Perguntas', value: 'answers'},
                {text: 'Ações', value: 'header', sortable: false}
            ],
        }
    },
    asyncComputed: {
        questionnaires: {
            get() {
                return new Promise((resolve, reject) => {
                    setTimeout(() => {
                        resolve(this.$store.state.questionnaires.all);
                    }, 1000)
                })
            },
            default: [],
        },
    },
    watch: {
        questionnaires: function(to, from) {
            this.isLoading = false;
        }
    },
    methods: {
        editQuest(questionnaire) {
            eventHub.$emit('questionnaire-edit', questionnaire);
        },
        deleteQuest(questionnaire) {
            eventHub.$emit('questionnaire-delete', questionnaire);
        },
        showQuest(questionnaire) {
            eventHub.$emit('questionnaire-show', questionnaire);
        },
    },
    mounted() {
        setTimeout(() => {
            this.isLoading = false;
        }, 5000);
    }
}
</script>
