import Vue from 'vue'
import M from "materialize-css";

/*
 * ------------------------------------------------------
 * Begin scoreboard related actions
 * ------------------------------------------------------
 */
const loadCurrentScoreboard = (context) => {
    Vue.axios.get('/api/scoreboards').then((response) => {
        context.commit('SET_CURRENT_SCOREBOARD', response.data)
        context.commit('CACHE_SCOREBOARD', response.data)
    }).catch((error) => {
        M.toast({html: "Could not load current scoreboard", classes: "red white-text"})
        throw new Error(`API ${error}`);
    })
};

const loadScoreboard = (context, payload) =>  {
    let commit = context.commit;
    let state = context.state;
    console.log('[store] Loading scoreboard for season: ' + payload.sid);
    console.log("Local state: ");
    console.log(state);
    if (state.scoreboards.all[payload.sid] !== undefined) {
        // We already have the scoreboard so use the cached version (no api calls)
        console.log("Scoreboard for season")
        commit('SET_CURRENT_SCOREBOARD', state.scoreboards.all[payload.sid]);
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
};

/*
 * ------------------------------------------------------
 * Begin teams related actions
 * ------------------------------------------------------
 */
const loadTeams = (context) => {
    Vue.axios.get('/api/teams').then((response) => {
        context.commit('SET_TEAMS', response.data);
    }).catch((error) => {
        M.toast({html: "Could not load team data", classes: "red white-text"})
        throw new Error(`API ${error}`);
    })
}

/*
 * ------------------------------------------------------
 * Begin season related actions
 * ------------------------------------------------------
 */
const loadSeasons = (context) => {
    Vue.axios.get('/api/seasons').then((response) => {
        context.commit('SET_SEASONS', response.data);
    }).catch((error) => {
        M.toast({html: "Could not load season data", classes: "red white-text"});
        throw new Error(`API ${error}`);
    })
}

/*
 * ------------------------------------------------------
 * Begin user related actions
 * ------------------------------------------------------
 */
const loginUser = (context, payload) => {
    context.commit('SET_TOKEN', payload.token);
    context.commit('SET_TOKEN_EXPIRES_IN', payload.expires_in)
    context.commit('SET_TOKEN_RECEIVE_TIME', Date.now());
}

const logoutUser = (context) => {
    context.commit('LOGOUT_USER');
}

export default {
    loadCurrentScoreboard,
    loadScoreboard,
    loadTeams,
    loadSeasons,
    loginUser,
    logoutUser
}