import Vue from 'vue'
import Vuex from 'vuex'
import actions from "@/store/actions";
import mutations from "@/store/mutations";
import getters from "@/store/getters";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      token: null,
      username: null,
      expiresIn: null,
      receiveTime: null
    },
    scoreboards: {
      current: {},
      all: {}
    },
    teams: [],
    seasons: []
  },
  mutations,
  actions,
  getters
})
