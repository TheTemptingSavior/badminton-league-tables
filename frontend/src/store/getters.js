const user = state => state.user;
const token = state => state.user.token;
const teams = state => state.teams;

export default {
    user,
    token,
    teams
}