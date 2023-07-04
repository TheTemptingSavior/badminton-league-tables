import Vue from 'vue'
import vuetify from "@/plugins/vuetify";
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'
import VueAxios from 'vue-axios'

import App from './App.vue'
import router from './router'
import store from './store/store'


Vue.config.productionTip = false
Vue.use(VueAxios, axios);

Vue.axios.interceptors.request.use(config => {
  console.log("Making request to: " + config.url);
  return config;
}, error => {
  return Promise.reject(error);
})

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')

/*
 * https://dev.to/viniciuskneves/watch-for-vuex-state-changes-2mgj
 * https://stackoverflow.com/questions/40564071/how-do-i-break-up-my-vuex-file
 */