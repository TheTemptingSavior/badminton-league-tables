import Vue from 'vue'
import Vuex from 'vuex'
import M from "materialize-css";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      token: null,
      username: null,
      expiresIn: null
    },
    scoreboards: {
      current: [],
      all: []
    },
    teams: [],
    seasons: []
  },
  mutations: {
    SET_TOKEN(state, token) {
      state.user.token = token;
    },
    SET_TOKEN_EXPIRES_IN(state, expiresIn) {
      state.user.expiresIn = expiresIn;
    },
    SET_CURRENT_SCOREBOARD(state, scoreboard) {
      state.scoreboards.current = scoreboard;
    },
    SET_TEAMS(state, teams) {
      state.teams = teams;
    },
    SET_SEASONS(state, seasons) {
      state.seasons = seasons;
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
    },
    loadSeasons({commit}) {
        Vue.axios.get('/api/seasons').then((response) => {
            commit('SET_SEASONS', response.data);
        }).catch((error) => {
          M.toast({html: "Could not load season data", classes: "red white-text"});
          throw new Error(`API ${error}`);
        })
    },
    setToken({commit}, payload) {
      commit('SET_TOKEN', payload.token);
      commit('SET_TOKEN_EXPIRES_IN', payload.expires_in)
    }
  },
  modules: {
  }
})
