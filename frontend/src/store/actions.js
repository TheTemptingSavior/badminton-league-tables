import Vue from 'vue'
import {EventBus} from "@/plugins/event-bus";

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
        console.log("Create a toast here to show error");
        EventBus.$emit('show-error', 'Failed loading scoreboard data');
        throw new Error(`API ${error}`);
    })
};

const loadScoreboard = (context, payload) =>  {
    let commit = context.commit;
    let state = context.state;
    console.log('[store] Loading scoreboard for season: ' + payload.sid);
    if (state.scoreboards.all[payload.sid] !== undefined) {
        // We already have the scoreboard so use the cached version (no api calls)
        console.log("Scoreboard for season exists locally")
        commit('SET_CURRENT_SCOREBOARD', state.scoreboards.all[payload.sid]);
    } else {
        // We do not have the scoreboard so go and get it
        Vue.axios.get('/api/scoreboards/' + payload.sid).then((response) => {
            commit('SET_CURRENT_SCOREBOARD', response.data);
            commit('CACHE_SCOREBOARD', response.data)
        }).catch((error) => {
            console.log("Create a toast here to show error");
            // M.toast({html: "Could not load scoreboard", classes: "red white-text"});
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
    if (context.state.teams.length !== 0) {
        // We already have the teams so don't load them again
        return;
    }
    Vue.axios.get('/api/teams').then((response) => {
        context.commit('SET_TEAMS', response.data);
    }).catch((error) => {
        console.log("Create a toast here to show error");
        // M.toast({html: "Could not load team data", classes: "red white-text"})
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
        console.log("Create a toast here to show error");
        // M.toast({html: "Could not load season data", classes: "red white-text"});
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

/*
 * ------------------------------------------------------
 * Begin tracker related actions
 * ------------------------------------------------------
 */
const loadCurrentTracker = (context) => {
    Vue.axios.get('/api/tracker').then((response) => {
        context.commit('SET_CURRENT_TRACKER', response.data);
        context.commit('CACHE_TRACKER', response.data);
    }).catch((error) => {
        console.log("Create a toast here to show error");
        // M.toast({html: "Could not load season data", classes: "red white-text"});
        throw new Error(`API ${error}`);
    });
};

const loadTracker = (context, payload) => {
    let commit = context.commit;
    let state = context.state;
    if (state.tracker.all[payload.sid] !== undefined) {
        // The tracker is already present
        commit('SET_CURRENT_TRACKER', state.tracker.all[payload.sid]);
    } else {
        // Tracker is not present
        Vue.axios.get('/api/tracker/' + payload.sid).then((response) => {
            commit('SET_CURRENT_TRACKER', response.data);
            commit('CACHE_TRACKER', response.data);
        }).catch((error) => {
            console.log("Create a toast here to show error");
            // M.toast({html: "Could not load season data", classes: "red white-text"});
            throw new Error(`API ${error}`);
        });
    }
}

export default {
    loadCurrentScoreboard,
    loadScoreboard,
    loadTeams,
    loadSeasons,
    loginUser,
    logoutUser,
    loadCurrentTracker,
    loadTracker,
}