import Vue from 'vue'
import {EventBus} from "@/plugins/event-bus";
import axios from 'axios'

/*
 * ------------------------------------------------------
 * Begin general store actions
 * ------------------------------------------------------
 */
const setLoading = (context) => {
    console.log("Setting the global loading flag");
    context.commit('SET_LOADING', true);
};

const loadCurrent = async (context) => {
    if (context.state.currentLoaded) {
        // The current state of the leaderboard is already loaded
        return;
    }
    console.log("Loading current season information");
    const routes = [
        Vue.axios.get("/api/seasons/current"),
        Vue.axios.get("/api/scoreboards/current"),
        Vue.axios.get("/api/tracker/current")
    ];
    Vue.axios.all(routes).then(axios.spread((r1, r2, r3) => {
        console.log("Received responses for current");
        context.commit('SET_CURRENT', [r1.data, r2.data, r3.data]);
    })).catch((error) => {
        console.log("Failed to get current data");
        EventBus.$emit('show-error', 'Failed loading data. Please refresh');
        throw new Error(`APIError: ${error}`);
    }).finally(() => {
        context.commit('SET_CURRENT_LOADED');
    });
}

const loadOther = async (context, payload) => {
    let state = context.state;
    if (state.currentLoaded) {
        if (state.all[state.current].season.id === payload.sid) {
            // The season to load is already loaded
            console.log("Chosen season (" + payload.sid + ") already loaded");
            return;
        }
        if (state.all[payload.sid] !== undefined) {
            // The slug is already in the all array
            console.log("Chosen season (" + payload.sid + ") is cached. Switching");
            // Switch the seasons
            context.commit('CHANGE_CURRENT', payload.sid);
            return;
        }
    }

    console.log("Loading data from season ID " + payload.sid);
    context.commit('UNSET_CURRENT_LOADED');
    Vue.axios.all([
        Vue.axios.get("/api/seasons/" + payload.sid),
        Vue.axios.get("/api/seasons/" + payload.sid + "/teams"),
        Vue.axios.get("/api/scoreboards/" + payload.sid),
        Vue.axios.get("/api/tracker/" + payload.sid)
    ]).then(axios.spread((r1, r2, r3, r4) => {
        console.log("Received responses for current");
        context.commit('SET_CURRENT', [{...r1.data, teams: r2.data}, r3.data, r4.data]);
        context.commit('SET_CURRENT_LOADED');
    })).catch((error) => {
        console.log("Failed to get current data");
        throw new Error(`APIError: ${error}`);
    });
}

const loadTeams = (context) => {
    if (context.state.teams !== null) {
        // We already have the teams so don't load them again
        return;
    }
    Vue.axios.get('/api/teams').then((response) => {
        context.commit('SET_TEAMS', response.data.data);
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
        context.commit('SET_SEASONS', response.data.data);
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


export default {
    setLoading,
    loadCurrent,
    loadOther,
    loadTeams,
    loadSeasons,
    loginUser,
    logoutUser,
}