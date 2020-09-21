import Vue from 'vue'
import Vuex from 'vuex'
import M from "materialize-css";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      token: null,
      username: null
    },
    scoreboards: {
      current: [],
      all: []
    },
    teams: []
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.user.token = token;
    },
    SET_CURRENT_SCOREBOARD(state, scoreboard) {
      state.scoreboards.current = scoreboard;
    },
    SET_TEAMS(state, teams) {
      state.teams = teams;
    }
  },
  actions: {
    loadScoreboard({commit}) {
      Vue.axios.get('/api/scoreboards').then((response) => {
        commit('SET_CURRENT_SCOREBOARD', response.data)
      }).catch((error) => {
        M.toast({html: "Could not load current scoreboard", classes: "red white-text"})
        throw new Error(`API ${error}`);
      })
    },
    loadTeams({commit}) {
      Vue.axios.get('/api/teams').then((response) => {
        commit('SET_TEAMS', response.data);
      }).catch((error) => {
        M.toast({html: "Could not load team data", classes: "red white-text"})
        throw new Error(`API ${error}`);
      })
    }
  },
  modules: {
  }
})
