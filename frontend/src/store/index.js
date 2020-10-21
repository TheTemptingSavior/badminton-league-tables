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
      current: {},
      all: {}
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
    },
    CACHE_SCOREBOARD(state, data) {
      let seasonId = data.season;
      state.scoreboards.all[seasonId] = data;
    }
  },
  actions: {
    loadCurrentScoreboard({commit}) {
      Vue.axios.get('/api/scoreboards').then((response) => {
        commit('SET_CURRENT_SCOREBOARD', response.data)
        commit('CACHE_SCOREBOARD', response.data)
      }).catch((error) => {
        M.toast({html: "Could not load current scoreboard", classes: "red white-text"})
        throw new Error(`API ${error}`);
      })
    },
    loadScoreboard({commit}, payload) {
      console.log('[store] Loading scoreboard for season: ' + payload.sid);
      if (this.state.scoreboards.all.sid !== undefined) {
        // We already have the scoreboard so use the cached version (no api calls)
        commit('SET_CURRENT_SCOREBOARD', this.state.scoreboards.all.sid);
      } else {
        // We do not have the scoreboard so go and get it
        Vue.axios.get('/api/scoreboards/' + payload.sid).then((response) => {
          commit('SET_CURRENT_SCOREBOARD', response.data);
          commit('CACHE_SCOREBOARD', response.data)
        }).catch((error) => {
          M.toast({html: "Could not load scoreboard", classes: "red white-text"});
          throw new Error(`API ${error}`);
        })
      }
      console.log(commit);
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
