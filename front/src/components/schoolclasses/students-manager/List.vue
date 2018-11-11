<template>
 <v-dialog
        v-model="dialog"
        max-width="550"
        scrollable
    >
        <v-card>
            <v-toolbar card color="amber" class="elevation-2">
                <v-btn icon @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title> Gerenciador de Alunos </v-toolbar-title>
                <v-spacer></v-spacer>
                <create-student :class_id="class_id"/>
            </v-toolbar>
                <v-layout column>
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
                        <td>{{props.item.id}}</td>
                        <td>{{props.item.name}}</td>
                    <td class="text-xs-left">
                    <v-icon
                        small
                        @click="deleteUser(props.item)"
                    > 
                        delete
                    </v-icon>
                </td>
            </template>
        </v-data-table>
        <delete-student/>

    </v-layout>
        </v-card>
 </v-dialog>
</template>

<script>
import { eventHub } from '../../../eventHub';
import CreateStudent from './Create';
import DeleteStudent from './Delete';

export default {
    components: {
        'create-student' : CreateStudent,
        'delete-student' : DeleteStudent,
    },
    data() {
        return {
            class_id: null,
            isLoading: true,
            dialog: false,
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
                {text: 'ID', value: 'id_student'},
                {text: 'Nome', value: 'name_student'},
                {text: 'Ações', value: 'header', sortable: false}
            ],
        }
    },
    computed: {
        usersTurmas(){
            return this.$store.getters['user/byClassId'](this.class_id);
        },
    },
    methods: {
        deleteUser(aluno) {
            eventHub.$emit('aluno-delete', aluno, this.class_id);
        },
    },
    mounted() {
        setTimeout(() => {
            this.isLoading = false;
        }, 5000);
    },
    created() {
        eventHub.$on('users-turmas-getted', (id) => {
            console.log('work');
            this.dialog = true;
            this.class_id = id;
            this.usersTurmas = this.$store.getters['user/byClassId'](id);
            this.isLoading = false;
        });
    },
}
</script>

