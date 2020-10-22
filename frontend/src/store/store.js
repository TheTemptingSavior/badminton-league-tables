import Vue from 'vue'
import Vuex from 'vuex'
import actions from "@/store/actions";
import mutations from "@/store/mutations";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      token: null,
      username: null,
      expiresIn: null
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
})
