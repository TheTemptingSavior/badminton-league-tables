import Vue from 'vue'
import 'materialize-css/dist/css/materialize.min.css'
import 'material-design-icons/iconfont/material-icons.css'
import axios from 'axios'
import VueAxios from 'vue-axios'

import App from './App.vue'
import router from './router'
import store from './store'


Vue.config.productionTip = false
Vue.use(VueAxios, axios);

// TODO: Somehow needs to be set with an environment variable
Vue.axios.defaults.baseURL = 'http://localhost';
Vue.axios.interceptors.request.use(config => {
  console.log("Making request to: " + config.url);
  return config;
}, error => {
  return Promise.reject(error);
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
