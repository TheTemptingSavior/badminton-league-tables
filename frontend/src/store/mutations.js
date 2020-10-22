/*
 * ------------------------------------------------------
 * Begin scoreboard related mutations
 * ------------------------------------------------------
 */
const SET_CURRENT_SCOREBOARD = (state, scoreboard) => {
    state.scoreboards.current = scoreboard;
}
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
    state.user.expiresIn = expiresIn;
}


export default {
    CACHE_SCOREBOARD,
    SET_CURRENT_SCOREBOARD,
    SET_TEAMS,
    SET_SEASONS,
    SET_TOKEN,
    SET_TOKEN_EXPIRES_IN
}