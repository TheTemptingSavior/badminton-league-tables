export default {
    loading: false,
    currentLoaded: false,
    user: {
        token: null,
        username: null,
        expiresIn: null,
        receiveTime: null
    },
    current: {
        season: null,
        teams: null,
        scoreboard: null,
        tracker: null
    },
    all: {},
    seasons: [],
    // ---
    scoreboards: {
        current: {},
        all: {}
    },
    tracker: {
        current: {},
        all: {}
    },
    teams: [],
}