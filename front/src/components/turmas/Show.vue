<template>
    <!-- 
        Criar um novo menu no canto esquerdo linkando os usuários com as turmas (Gerenciar Alunos)
        O menu vai representar as turmas no canto esquerdo
        Os alunos ligados a tais turmas no meio
        E as ações no canto direito (Adicionar, Editar e Remover)

        Com isso será chamado apenas uma vez o users_turmas no nível do Default.template
     -->
    <div>
        <v-layout mb-1>
            <v-flex xs12>
                <v-card color="white">
                    <v-card-title>
                        <h2 class="display-1">{{ turma.title }} </h2>
                        <v-divider vertical class="mx-3"/>
                        <h2> {{ teacher.name }} </h2>
                        <v-spacer></v-spacer>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap>

        <sections :turma="turma"/>

        <v-flex xs12>
            <v-btn to="/projects">Voltar</v-btn>
        </v-flex>

        <show-tasks/>
    </v-layout>
    </div>    
</template>

<script>
    import Sections from '../sections/List';
    import ShowTask from '../tasks/Show';

    export default {
        computed: {
            turma() {
                const id = this.$route.params.id;
                return this.$store.getters['projects/byId'](id);
            },
            teacher() {
                const id = this.turma['id_professor'];
                return this.$store.getters['user/byId'](id);
            },
        },
        components: {
            'sections'      : Sections,
            'show-tasks'    : ShowTask,
        },
    }
</script>