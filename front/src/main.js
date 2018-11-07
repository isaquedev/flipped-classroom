// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import './axios';
import AsyncComputed from 'vue-async-computed'
import VueYoutube from 'vue-youtube'
import store from './store';

Vue.config.productionTip = false

Vue.use(Vuetify);
Vue.use(AsyncComputed);
Vue.use(VueYoutube);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
