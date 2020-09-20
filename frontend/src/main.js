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
Vue.axios.defaults.baseURL = 'http://localhost';

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
