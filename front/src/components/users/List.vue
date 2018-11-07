<template>
    <v-layout column>
        <v-toolbar class="elevation-1" flat color="white">
            <v-toolbar-title>Usuários</v-toolbar-title>
                <v-divider
                    class="mx-2"
                    inset
                    vertical
                ></v-divider>
                        
                <v-spacer></v-spacer>

                <create-user/>

                </v-toolbar>
                    <v-data-table
                        :headers="headers"
                        :items="users"
                        :loading="isLoading"
                        :pagination.sync="pagination"
                        :total-items="totalUsers"
                        no-data-text="Nenhum usuário encontrado"
                        class="elevation-1"
                    >
                    <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                    <template slot="items" slot-scope="props">
                        <td>{{ props.item.header}}</td>
                        <td >{{props.item.id}}</td>
                        <td >{{props.item.name}}</td>
                        <td >{{props.item.login}}</td>
                        <td class="text-xs-center">{{props.item.type}}</td>
                    <td class="text-xs-left">
                    <v-icon
                        small
                        @click="editUser(props.item)"
                    >
                        edit
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

        <delete-user/>
        <edit-user/>

    </v-layout>
</template>

<script>
import { eventHub } from '../../eventHub';
import CreateUser from './Create';
import EditUser from './Edit';
import DeleteUser from './Delete';

export default {
    components: {
        'create-user' : CreateUser,
        'delete-user' : DeleteUser,
        'edit-user': EditUser,
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
                {text: 'Nome', value: 'name'},
                {text: 'Login', value: 'login'},
                {text: 'Tipo Usuário', value: 'type'},
                {text: 'Ações', value: 'header', sortable: false}
            ],
        }
    },
    asyncComputed: {
        users: {
            get() {
                return new Promise((resolve, reject) => {
                    setTimeout(() => {
                        resolve(this.$store.state.user.all);
                    }, 1000)
                })
            },
            default: [],
        },
    },
    watch: {
        users: function(to, from) {
            this.isLoading = false;
        }
    },
    methods: {
        editUser(user) {
            eventHub.$emit('user-edit', user);
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

