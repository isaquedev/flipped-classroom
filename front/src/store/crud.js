import _ from 'underscore';
import axios from 'axios';
const qs = require('qs');

export default function (endpoint) {
    const state = {
        all: [],
        teachers: [],
        user: [],
        users_turmas: [],
    }
    
    const getters = {
        byId: state => (id) => {
            const data = _.find(state.all, (project) => {
                return project.id == id;
            });
    
            return data || {};
        },
        students: state => (index) => {            
            const students = _.filter(state.users_turmas[index]['stundents_list'], (student) => {
                return student['id'];
            });

            let result = [];
            state.all.forEach((user, key) => {
                if (user['user_type'] == 'Aluno') {
                    let isStudent = false;
                    students.forEach(student => {
                        if (user.id == student.student_id){
                            isStudent = true;
                        }
                    })
                    if (!isStudent) {
                        result[key] = user['id'] + ' - ' + user['name'];
                    }
                }
            });

            return result;
        }
    }
    
    const mutations = {
        setUser(state, user) {
            state.user = user;
        },
        updateTurma(state, data) {            
            for (let i = 0; i < state.all.length; i++) {
                if (state.all[i]['id'] === data['id']){
                    state.all[i]['title'] = data['title'];
                    state.all[i]['id_professor'] = data['id_professor'];
                    break;
                }
            }
        },
        updateUserTurmas(state, data) {

        },
        updateUser(state, data) {
            data['user_type'] = data['user_type'] == 1 ? 'Professor' : 'Aluno';
            for (let i = 0; i < state.all.length; i++) {
                if (state.all[i]['id'] === data['id']){
                    state.all[i]['name'] = data['name'];
                    state.all[i]['login'] = data['login'];
                    state.all[i]['user_type'] = data['user_type'];
                    break;
                }
            }
            if(data['user_type'] == 'Professor') {
                for (let i = 0; i < state.teachers.length; i++) {
                    if (state.teachers[i]['id'] == data['id']){
                        state.teachers[i]['name'] = data['name'];
                    }
                }
            }
        },
        setUsersTurmas(state, data) {
            state.users_turmas = data;
        },
        updateAll(state, data) {
            state.all = data;
        },
        
        merge(state, data) {
            state.all.push(data);

        },
        mergeUser(state, data) {
            data['user_type'] = data['user_type'] == 1 ? 'Professor' : 'Aluno';
            state.teachers.push(data);
            state.all.push(data);
        },
        mergeUserTurma(state, data) {
            
        },
        delete(state, data) {
            for (let index = 0; index < state.all.length; index++) {
                if (state.all[index]['id'] == data['id'])
                state.all.splice(index, 1);
            }
        },
        getTeachers(state, data) {
            state.teachers = data;
        },
    }
    
    const actions = {
        getUser(context) {
            return axios.get(endpoint).then ((res) => {
                context.commit('setUser', res.data);
            });
        },
        getAll(context, id) {
            let url = endpoint;
            if (id) {
                url += '?id=' + id;
            }
            return axios.get(url).then((res) => {
                context.commit('updateAll', res.data);
            });
        },
        create(context, data) {
            data = qs.stringify(data);
            return axios.post(endpoint, data).then((res) => {
                if (endpoint.split('/')[2] == 'user') {
                    context.commit('mergeUser', res.data);
                } else {
                    context.commit('merge', res.data);
                }
            });
        },
        createUserTurma(context, data) {
            context.commit('mergeUserTurma', data);
        },
        update(context, data)  {
            let url = endpoint + '?id='+data[0];
            data[1] = qs.stringify(data[1]);
            return axios.put(url, data[1]).then((res) => {
                let updated = qs.parse(res.config.data);
                updated['id'] = data[0];
                let moduleName = endpoint.split('/')[2];
                if (moduleName == 'user') {
                    context.commit('updateUser', res.data);
                    /**TODO
                     * Atualizar o usersTurmas
                     */
                } else if(moduleName == 'projects') {
                    context.commit('updateTurma', updated);
                    /**TODO
                     * Atualizar o usersTurmas
                     */
                }
            })
        },
        delete(context, id) {
            return axios.delete(endpoint + '?id=' + id).then(res => {
                context.commit('delete', res.data);
            })
        },        
        getTeachers(context) {
            let url = '/api/teachers';
            return axios.get(url).then((res) => {
                context.commit('getTeachers', res.data);
            })
        }, 
        getUsers(context) {
            let url = endpoint + '_getall';
            return axios.get(url).then((res) => {
                context.commit('updateAll', res.data);
            })
        },
        getUsersTurmas(context) {
            let url = endpoint + '/turmas';
            return axios.get(url).then((res) => {
                context.commit('setUsersTurmas', res.data);
            })
        },
        getClassViewPermission(context, turma_id) {
            let url = endpoint + '/permission?turma_id=' + turma_id;
            return axios.get(url);
        },
    }
    
    return {
        state,
        actions,
        mutations,
        getters,
        namespaced: true
    }
}