import axios from 'axios';
const qs = require('qs');

const state = {
    isLogged: false,
    user: [],
}

const mutations = {
    updateLogged(state, data) {
        state.isLogged = data;
    },
    updateUser(state, user) {
        state.user = user;
    },
}

const actions = {
    login(context, data) {
        data = qs.stringify(data);
        return axios.post('/auth/token', data).then((res) => {
            window.axios.defaults.headers.common['Authorization'] = res.data.token;
            window.localStorage.setItem('token', res.data.token);
            context.commit('updateLogged', true);
            return res.data;
        })
    },
    logout(context) {
        window.localStorage.removeItem('token');
        context.commit('updateLogged', false);
    },
    getUser(context) {
        return axios.get('/api/me').then ((res) => {
            context.commit('updateUser', res.data);
        });
    },
}

const namespaced = true;

export default {
    state,
    mutations,
    actions,
    namespaced
}