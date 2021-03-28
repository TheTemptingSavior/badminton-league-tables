const getScoreboard = state => state.all[state.current].scoreboard;
const getSeason = state => state.all[state.current].season;
const getTeams = state => state.all[state.current].teams;
const getTracker = state => state.all[state.current].tracker;

const allTeams = state => {
    if (state.teams === null) {
        return [];
    } else {
        return state.teams;
    }
}
const getTeam = (state) => (index) => {
    if (state.teams === null) {
        return {};
    } else {
        return state.teams[index];
    }
}


const user = state => state.user;
const token = state => state.user.token;
const teams = state => state.teams;

export default {
    getScoreboard,
    getSeason,
    getTeams,
    getTracker,

    allTeams,
    getTeam,

    user,
    token,
    teams
}