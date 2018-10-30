<template>
    <v-layout column>
        <v-toolbar class="elevation-1" flat color="white">
            <v-toolbar-title>Gerenciamento de Alunos</v-toolbar-title>
                <v-divider
                    class="mx-2"
                    inset
                    vertical
                ></v-divider>
                        
                <v-spacer></v-spacer>               

                </v-toolbar>
                    <v-data-table
                        :headers="headers"
                        :items="usersTurmas"
                        :loading="isLoading"
                        :pagination.sync="pagination"
                        :total-items="totalUsersTurmas"
                        no-data-text="Nenhum aluno associado a turma foi encontrado"
                        class="elevation-1"
                    >
                    <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                    <template slot="items" slot-scope="props">
                        <td>{{props.item.header}}</td>
                        <td>{{props.item.turma_id}}</td>
                        <td>{{props.item.turma_name}}</td>
                        <td>{{props.item.stundents_list}}</td>
                    <td class="text-xs-left">
                    <v-icon
                        small
                        @click="addUser(props.item)"
                    >
                        add
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteUser(props.item)"
                    > 
                        delete
                    </v-icon>
                </td>
            </template>
        </v-data-table>
         <create-student/>
        <!--delete-user/>
        <edit-user/>-->

    </v-layout>
</template>

<script>
import { eventHub } from '../../eventHub';
import CreateStudent from './Create';
//import EditUser from './Edit';
//import DeleteUser from './Delete';

export default {
    components: {
        'create-student' : CreateStudent,/*
        'delete-user' : DeleteUser,
        'edit-user': EditUser,*/
    },
    data() {
        return {
            isLoading: true,
            pagination: {
                page: 1,
                rowsPerPage: 10,
            },
            totalUsersTurmas: 0,
            headers: [
                {
                    align: 'left',
                    sortable: false,
                    value: 'header'
                }, 
                {text: 'ID (Turma)', value: 'id_turma'},
                {text: 'Turma', value: 'turma'},
                {text: 'Estudantes na Turma', value: 'studenst_list'},
                {text: 'Ações', value: 'header', sortable: false}
            ],
        }
    },
    asyncComputed: {
        usersTurmas: {
            get() {
                return new Promise((resolve, reject) => {
                    setTimeout(() => {
                        resolve(this.$store.state.user.users_turmas);
                    }, 1000)
                })
            },
            default: [],
        },
    },
    watch: {
        usersTurmas: function(to, from) {
            this.isLoading = false;
        }
    },
    methods: {
        addUser(turma) {
            let index = this.usersTurmas.indexOf(turma);
            eventHub.$emit('user-add', turma, index);
        },
        deleteUser(user) {
            eventHub.$emit('user-delete', user);
        },
    },
    mounted() {
        setTimeout(() => {
            this.isLoading = false;
        }, 5000);
    }
}
</script>

