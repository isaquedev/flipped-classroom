import _ from 'underscore';
import axios from 'axios';
const qs = require('qs');

export default function (endpoint) {
    const state = {
        all: [],
        teachers: [],
        users_turmas: [],
        contador: false,
    }
    
    const getters = {
        byId: state => (id) => {
            const data = _.find(state.all, (obj) => {
                return obj.id == id;
            });
    
            return data || {};
        },
        byClassId: state => (classId) => {
            //Rodar usersturmas para pegar os alunos de lá
            //fazer parte do backend para adiconar aluno ao users_turmas;
            const request_class = _.find(state.users_turmas, (_class) => {
                return _class['schoolclass_id'] == classId;
            });
            return request_class['students_list'];
        },
        byNotClassId: state => (classId) => {
            //Rodar users para pegar alunos de lá
            const request_class = _.find(state.users_turmas, (_class) => {
                return _class['schoolclass_id'] == classId;
            });
            
            let result = [];
            state.all.forEach((user, key) => {
                if (user['type'] == 'Aluno') {
                    let isInClass = false;
                    request_class['students_list'].forEach(student => {
                        if (user.id == student.id){
                            isInClass = true;
                        }
                    })
                    if (!isInClass) {
                        result[key] = user['id'] + ' - ' + user['name'];
                    }
                }
            });

            var resultClean = _.filter(result, (student) => {
                return typeof student != 'undefined';
            })

            return resultClean;
        }, 
        filter: state => () => {
            const filteredQuestionnaires = [];
            state.all.forEach((questionnaire, q_key) => {
                filteredQuestionnaires[q_key] = questionnaire['id'] + ' - ' + questionnaire['title'];
            });
            return filteredQuestionnaires;
        }
    }
    
    const mutations = {
        addQuestion(state, data){
            state.all.push(data);
        },
        delQuestion(state, id){
            state.all.splice(id, 1);
        },
        edtQuestion(state, data){
            state.all.splice(data[0],0,data[1]);
            state.all.splice(data[0] + 1, 1);
        },
        updateQuestionnaire(state, data){
            for (let i = 0; i < state.all.length; i++) {
                if (state.all[i]['id'] === data['id']){
                    state.all[i]['title'] = data['title'];
                    state.all[i]['is_public'] = data['is_public'];
                    state.all[i]['is_test'] = data['is_test'];
                    state.all[i]['random_answers'] = data['random_answers'];
                    break;
                }
            }
        },
        updateClass(state, data) {            
            for (let i = 0; i < state.all.length; i++) {
                if (state.all[i]['id'] === data['id']){
                    state.all[i]['title'] = data['title'];
                    state.all[i]['id_teacher'] = data['id_teacher'];
                    break;
                }
            }
        },
        updateUser(state, data) {
            data['type'] = data['type'] == 1 ? 'Professor' : 'Aluno';
            for (let i = 0; i < state.all.length; i++) {
                if (state.all[i]['id'] === data['id']){
                    state.all[i]['name'] = data['name'];
                    state.all[i]['login'] = data['login'];
                    state.all[i]['type'] = data['type'];
                    break;
                }
            }
            if(data['type'] == 'Professor') {
                for (let i = 0; i < state.teachers.length; i++) {
                    if (state.teachers[i]['id'] == data['id']){
                        state.teachers[i]['name'] = data['name'];
                    }
                }
            }
        },
        updateStudentsList(state, data) {
            state.users_turmas.forEach((classes, c_key) => {
                classes['students_list'].forEach((student, s_key) => {
                    if (student['id'] == data['id']){
                        state.users_turmas[c_key]['students_list'][s_key]['name'] = data['name'];
                    }
                })
            });
        },
        addStudentsList(state, data) {
            state.users_turmas.push({'schoolclass_id': data, students_list: []})
        },
        deleteStudentsList(state, data) {
            state.users_turmas.forEach((classes, c_key) => {
                classes['students_list'].forEach((student, s_key) => {
                    if (student['id'] == data){
                        state.users_turmas[c_key]['students_list'].splice(s_key, 1);
                    }
                })
            });
        },
        updateUsersSchoolClasses(state, data) {
            state.users_turmas = data;
        },
        updateAll(state, data) {
            state.all = data;
        },
        
        merge(state, data) {
            state.all.push(data);

        },
        mergeUser(state, data) {
            data['type'] = data['type'] == 1 ? 'Professor' : 'Aluno';
            if(data['type'] == "Professor"){
                state.teachers.push(data);
            }
            state.all.push(data);
        },
        mergeUserTurma(state, data) {
            state.users_turmas.forEach((schoolclass, key) => {
                if (schoolclass['schoolclass_id'] == data['class_id']){
                    let student = {
                        id: data['student']['id'],
                        name: data['student']['name']
                    }
                    state.users_turmas[key]['students_list']
                        .push(student);
                }
            })
        },
        delete(state, data) {
            console.log(data);
            for (let index = 0; index < state.all.length; index++) {
                if (state.all[index]['id'] == data['id'])
                state.all.splice(index, 1);
            }
        },
        deleteUsersTurmas(state, data) {
            state.users_turmas.forEach((schoolclass, sc_key) => {
                if (schoolclass['schoolclass_id'] == data['class_id']){
                    state.users_turmas[sc_key]['students_list'].forEach((student, s_key) => {
                        if (data['student']['id'] == student['id']) {
                            state.users_turmas[sc_key]['students_list'].splice(s_key, 1);
                        }
                    })
                }
            })
        },
        getTeachers(state, data) {
            state.teachers = data;
        },
        clean(state){
            state.all = [];
        },
    }
    
    const actions = {
        addQuestion(context, data) {
            data = qs.stringify(data);
            let url = endpoint + '/return';
            return axios.post(url, data).then((res) => {
                context.commit('addQuestion', res.data);
            });
        },
        getAll(context, id) {
            let url = endpoint;
            if (id) {
                url += '/' + id;
            }
            return axios.get(url).then((res) => {
                context.commit('updateAll', res.data);
            });
        },
        create(context, data) {
            data = qs.stringify(data);
            let moduleName = endpoint.split('/')[2];
            return axios.post(endpoint, data).then((res) => {
                if (moduleName == 'user') {
                    context.commit('mergeUser', res.data);
                } else if (moduleName == 'questionnaires'){
                    context.commit('merge', res.data);
                } else {
                    context.commit('merge', res.data);
                }
            });
        },
        createQuestion(context, data) {
            data = qs.stringify(data);
            return axios.post(url, data).then(() => {
            })
        },
        update(context, data)  {
            let url = endpoint;
            data = qs.stringify(data);
            return axios.put(url, data).then((res) => {
                let updated = qs.parse(res.data);
                let moduleName = endpoint.split('/')[2];
                if (moduleName == 'user') {
                    context.commit('updateUser', updated);
                } else if(moduleName == 'schoolclasses') {
                    context.commit('updateClass', updated);
                } else if(moduleName == 'questionnaires'){
                    context.commit('updateQuestionnaire', res.data);
                } else if(moduleName == 'question'){
                    context.commit('updateAll', updated);
                }
            })
        },
        delete(context, id) {
            let url = endpoint;
            if (id) {
                url += '/' + id;
            }
            return axios.delete(url).then(res => {
                context.commit('delete', res.data);
            })
        },
        createUserTurma(context, data) {
            let url = endpoint + '/turmas';
            data = qs.stringify(data);
            return axios.post(url, data).then((res) => {
                context.commit('mergeUserTurma', res.data);
            });            
            //Colocar em "create"
        },
        getUsersTurmas(context) {
            let url = endpoint + '/turmas';
            return axios.get(url).then((res) => {
                context.commit('updateUsersSchoolClasses', res.data);
            })
        },
        deleteUsersTurmas(context, data) {
            let url = endpoint + '/turmas/' + data[0] + '/' + data[1];
            return axios.delete(url).then((res) => {
                context.commit('deleteUsersTurmas', res.data);
            })
        },
        getTeachers(context) {
            let url = '/api/teachers';
            return axios.get(url).then((res) => {
                context.commit('getTeachers', res.data);
            })
        }, 
        getUsers(context) {
            let url = endpoint + 's';
            return axios.get(url).then((res) => {
                context.commit('updateAll', res.data);
            })
        },
        getClassViewPermission(context, turma_id) {
            let url = endpoint;
            if (turma_id) {
                url += '/permission/' + turma_id;
            }
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