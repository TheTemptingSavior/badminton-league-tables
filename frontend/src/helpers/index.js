export function isLoggedIn(user) {
    // Check the user is actually logged in before showing this
    if (user.token !== null && user.expiresIn !== null && user.receiveTime !== null) {
        if (Date.now() < (user.receiveTime + user.expiresIn)) {
            return true
        }
    }

    return false;
}

export function transformScorecard(id, scorecard) {
    return {
        id: id,
        home_team: scorecard.homeTeam,
        away_team: scorecard.awayTeam,
        home_points: scorecard.homePoints,
        away_points: scorecard.awayPoints,
        date_played: scorecard.datePlayed,
        // Home Players
        home_player_1: scorecard.homePlayers.player1,
        home_player_2: scorecard.homePlayers.player2,
        home_player_3: scorecard.homePlayers.player3,
        home_player_4: scorecard.homePlayers.player4,
        home_player_5: scorecard.homePlayers.player5,
        home_player_6: scorecard.homePlayers.player6,
        // Away Players
        away_player_1: scorecard.awayPlayers.player1,
        away_player_2: scorecard.awayPlayers.player2,
        away_player_3: scorecard.awayPlayers.player3,
        away_player_4: scorecard.awayPlayers.player4,
        away_player_5: scorecard.awayPlayers.player5,
        away_player_6: scorecard.awayPlayers.player6,
        // Home 1 vs Away 1
        game_one_v_one_home_one: scorecard.games.game1.home1,
        game_one_v_one_away_one: scorecard.games.game1.away1,
        game_one_v_one_home_two: scorecard.games.game1.home2,
        game_one_v_one_away_two: scorecard.games.game1.away2,
        game_one_v_one_home_three: scorecard.games.game1.home3,
        game_one_v_one_away_three: scorecard.games.game1.away3,
        // Home 1 vs Away 2
        game_one_v_two_home_one: scorecard.games.game2.home1,
        game_one_v_two_away_one: scorecard.games.game2.away1,
        game_one_v_two_home_two: scorecard.games.game2.home2,
        game_one_v_two_away_two: scorecard.games.game2.away2,
        game_one_v_two_home_three: scorecard.games.game2.home3,
        game_one_v_two_away_three: scorecard.games.game2.away3,
        // Home 1 vs Away 3
        game_one_v_three_home_one: scorecard.games.game3.home1,
        game_one_v_three_away_one: scorecard.games.game3.away1,
        game_one_v_three_home_two: scorecard.games.game3.home2,
        game_one_v_three_away_two: scorecard.games.game3.away2,
        game_one_v_three_home_three: scorecard.games.game3.home3,
        game_one_v_three_away_three: scorecard.games.game3.away3,
        // Home 2 vs Away 1
        game_two_v_one_home_one: scorecard.games.game4.home1,
        game_two_v_one_away_one: scorecard.games.game4.away1,
        game_two_v_one_home_two: scorecard.games.game4.home2,
        game_two_v_one_away_two: scorecard.games.game4.away2,
        game_two_v_one_home_three: scorecard.games.game4.home3,
        game_two_v_one_away_three: scorecard.games.game4.away3,
        // Home 2 vs Away 2
        game_two_v_two_home_one: scorecard.games.game5.home1,
        game_two_v_two_away_one: scorecard.games.game5.away1,
        game_two_v_two_home_two: scorecard.games.game5.home2,
        game_two_v_two_away_two: scorecard.games.game5.away2,
        game_two_v_two_home_three: scorecard.games.game5.home3,
        game_two_v_two_away_three: scorecard.games.game5.away3,
        // Home 2 vs Away 3
        game_two_v_three_home_one: scorecard.games.game6.home1,
        game_two_v_three_away_one: scorecard.games.game6.away1,
        game_two_v_three_home_two: scorecard.games.game6.home2,
        game_two_v_three_away_two: scorecard.games.game6.away2,
        game_two_v_three_home_three: scorecard.games.game6.home3,
        game_two_v_three_away_three: scorecard.games.game6.away3,
        // Home 3 vs Away 1
        game_three_v_one_home_one: scorecard.games.game7.home1,
        game_three_v_one_away_one: scorecard.games.game7.away1,
        game_three_v_one_home_two: scorecard.games.game7.home2,
        game_three_v_one_away_two: scorecard.games.game7.away2,
        game_three_v_one_home_three: scorecard.games.game7.home3,
        game_three_v_one_away_three: scorecard.games.game7.away3,
        // Home 3 vs Away 2
        game_three_v_two_home_one: scorecard.games.game8.home1,
        game_three_v_two_away_one: scorecard.games.game8.away1,
        game_three_v_two_home_two: scorecard.games.game8.home2,
        game_three_v_two_away_two: scorecard.games.game8.away2,
        game_three_v_two_home_three: scorecard.games.game8.home3,
        game_three_v_two_away_three: scorecard.games.game8.away3,
        // Home 3 vs Away 3
        game_three_v_three_home_one: scorecard.games.game9.home1,
        game_three_v_three_away_one: scorecard.games.game9.away1,
        game_three_v_three_home_two: scorecard.games.game9.home2,
        game_three_v_three_away_two: scorecard.games.game9.away2,
        game_three_v_three_home_three: scorecard.games.game9.home3,
        game_three_v_three_away_three: scorecard.games.game9.away3,
    }
}
