const SET_CURRENT = (state, responses) => {
    let season = responses[0];
    state.current = season.id
    state.all[season.id] = {
        season: {
            id: responses[0].id,
            slug: responses[0].slug,
            start: responses[0].start,
            end: responses[0].end,
        },
        teams: responses[0].teams,
        scoreboard: responses[1].data,
        tracker: responses[2]
    }
}

const SET_CURRENT_LOADED = (state) => {
    state.currentLoaded = true;
}
const UNSET_CURRENT_LOADED = (state) => {
    state.currentLoaded = false;
}

const CHANGE_CURRENT = (state, key) => {
    if (state.all[key] === undefined) {
        console.error("Key not found in the store");
        return;
    }
    state.current = key;
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
    CHANGE_CURRENT,

    CACHE_SCOREBOARD,
    SET_TEAMS,
    SET_SEASONS,
    SET_TOKEN,
    SET_TOKEN_EXPIRES_IN,
    SET_TOKEN_RECEIVE_TIME,
    LOGOUT_USER,
    CACHE_TRACKER,
}