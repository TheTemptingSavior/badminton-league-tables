const SET_CURRENT = (state, responses) => {
    state.current.season = {
        id: responses[0].id,
        slug: responses[0].slug,
        start: responses[0].start,
        end: responses[0].end,
    }
    state.current.teams = responses[0].teams;
    state.current.scoreboard = responses[1].data;
    state.current.tracker = responses[2];
}

const SET_CURRENT_LOADED = (state) => {
    state.currentLoaded = true;
}
const UNSET_CURRENT_LOADED = (state) => {
    state.currentLoaded = false;
}

const CACHE_CURRENT = (state) => {
    let key;
    try {
        key = state.current.season.slug
    } catch (e) {
        console.error("Could not cache current object. No season slug found to use as key");
        return;
    }
    state.all[key] = state.current;
}

/*
 * ------------------------------------------------------
 * General state mutators
 * ------------------------------------------------------
 */
const SET_LOADING = (state, value) => {
    state.loading = value;
}

/*
 * ------------------------------------------------------
 * Begin scoreboard related mutations
 * ------------------------------------------------------
 */
// const SET_CURRENT_SCOREBOARD = (state, scoreboard) => {
//     state.scoreboards.current = scoreboard;
// }
const CACHE_SCOREBOARD = (state, data) => {
    let seasonId = data.season;
    state.scoreboards.all[seasonId] = data;
}

/*
 * ------------------------------------------------------
 * Begin teams related mutations
 * ------------------------------------------------------
 */
const SET_TEAMS = (state, teams) => {
    state.teams = teams;
}

/*
 * ------------------------------------------------------
 * Begin season related mutations
 * ------------------------------------------------------
 */
const SET_SEASONS = (state, seasons) => {
    state.seasons = seasons;
}

/*
 * ------------------------------------------------------
 * Begin user related mutations
 * ------------------------------------------------------
 */
const SET_TOKEN = (state, token) => {
    state.user.token = token;
}
const SET_TOKEN_EXPIRES_IN = (state, expiresIn) => {
    // The default expires in payload is in seconds
    // multiply up to milliseconds for use with epoch times
    state.user.expiresIn = expiresIn * 1000;
}
const SET_TOKEN_RECEIVE_TIME = (state, receiveTime) => {
    state.user.receiveTime = receiveTime;
}
const LOGOUT_USER = (state) => {
    state.user.token = null
    state.user.username = null
    state.user.expiresIn = null
    state.user.receiveTime = null
}

/*
 * ------------------------------------------------------
 * Begin tracker related mutations
 * ------------------------------------------------------
 */
// const SET_CURRENT_TRACKER = (state, payload) => {
//     state.tracker.current = payload;
// }

const CACHE_TRACKER = (state, payload) => {
    let seasonId = payload.season.id;
    state.tracker.all[seasonId] = payload;
}

export default {
    SET_LOADING,
    SET_CURRENT,
    SET_CURRENT_LOADED,
    UNSET_CURRENT_LOADED,
    CACHE_CURRENT,

    CACHE_SCOREBOARD,
    SET_TEAMS,
    SET_SEASONS,
    SET_TOKEN,
    SET_TOKEN_EXPIRES_IN,
    SET_TOKEN_RECEIVE_TIME,
    LOGOUT_USER,
    CACHE_TRACKER,
}