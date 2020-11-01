<template>
  <div id="admin-new-scorecard" class="container">
    <GameModal id="game-modal" :title="currentModal" />
    <h3 class="title center">New Scorecard</h3>
    <div id="new-card-form">
      <div class="row">
        <form class="col s12">
          <fieldset id="required-fieldset" style="border: solid red 1px">
            <legend>Required Fields</legend>
            <div class="row">
              <div class="input-field col s12">
                <select id="home-team" v-model="scorecard.homeTeam">
                  <option value="" disabled selected>Select home team</option>
                  <option v-for="team in getTeams" :key="team.id" :value="team.id">{{ team.name }}</option>
                </select>
                <span class="helper-text">Home Team</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <select id="away-team" v-model="scorecard.awayTeam">
                  <option value="" disabled selected>Select away team</option>
                  <option v-for="team in getTeams" :key="team.id" :value="team.id">{{ team.name }}</option>
                </select>
                <span class="helper-text">Away Points</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input type="date" id="date-played" class="validate" v-model="scorecard.datePlayed" />
                <span class="helper-text">Date Played</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6">
                <input type="number" id="home-points" class="validate" min="0" max="9" step="1" v-model.number="scorecard.homePoints" />
                <span class="helper-text">Home Points</span>
              </div>
              <div class="input-field col s6">
                <input type="number" id="away-points" class="validate" min="0" max="9" step="1" v-model.number="scorecard.awayPoints" />
                <span class="helper-text">Away Points</span>
              </div>
            </div>
          </fieldset>
          <fieldset id="players-fieldset">
            <legend>Players</legend>
            <div class="row">
              <div class="col m6">
                <div class="input-field col s12">
                  <input type="text" id="home-player-1" class="validate" v-model="scorecard.homePlayers.player1" />
                  <label for="home-player-1">Home Player 1</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="home-player-2" class="validate" v-model="scorecard.homePlayers.player2" />
                  <label for="home-player-2">Home Player 2</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="home-player-3" class="validate" v-model="scorecard.homePlayers.player3" />
                  <label for="home-player-3">Home Player 3</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="home-player-4" class="validate" v-model="scorecard.homePlayers.player4" />
                  <label for="home-player-4">Home Player 4</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="home-player-5" class="validate" v-model="scorecard.homePlayers.player5" />
                  <label for="home-player-5">Home Player 5</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="home-player-6" class="validate" v-model="scorecard.homePlayers.player6" />
                  <label for="home-player-6">Home Player 6</label>
                </div>
              </div>
              <div class="show-on-small hide-on-med-and-up center-align">
                <h5>vs</h5>
              </div>
              <div class="col m6">
                <div class="input-field col s12">
                  <input type="text" id="away-player-1" class="validate" v-model="scorecard.awayPlayers.player1" />
                  <label for="away-player-1">Away Player 1</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="away-player-2" class="validate" v-model="scorecard.awayPlayers.player2" />
                  <label for="away-player-2">Away Player 2</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="away-player-3" class="validate" v-model="scorecard.awayPlayers.player3" />
                  <label for="away-player-3">Away Player 3</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="away-player-4" class="validate" v-model="scorecard.awayPlayers.player4" />
                  <label for="away-player-4">Away Player 4</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="away-player-5" class="validate" v-model="scorecard.awayPlayers.player5" />
                  <label for="away-player-5">Away Player 5</label>
                </div>
                <div class="input-field col s12">
                  <input type="text" id="away-player-6" class="validate" v-model="scorecard.awayPlayers.player6" />
                  <label for="away-player-6">Away Player 6</label>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset id="scores-fieldset">
            <legend>Games</legend>
            <p>Click on a card to fill in the game</p>
            <div class="row">
              <div class="col s12 m4">
                <div class="card-panel orange hoverable" v-on:click="openGameModal($event, 'game1', '1 v 1')">
                    <h5 class="center-align white-text">1 v 1</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable"  v-on:click="openGameModal($event, 'game2', '1 v 2')">
                    <h5 class="center-align white-text">1 v 2</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable" v-on:click="openGameModal($event, 'game3', '1 v 3')">
                    <h5 class="center-align white-text">1 v 3</h5>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable" v-on:click="openGameModal($event, 'game4', '2 v 1')">
                  <h5 class="center-align white-text">2 v 1</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel orange hoverable" v-on:click="openGameModal($event, 'game5', '2 v 2')">
                  <h5 class="center-align white-text">2 v 2</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable" v-on:click="openGameModal($event, 'game6', '2 v 3')">
                  <h5 class="center-align white-text">3 v 3</h5>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable" v-on:click="openGameModal($event, 'game7', '3 v 1')">
                  <h5 class="center-align white-text">3 v 1</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel light-blue hoverable" v-on:click="openGameModal($event, 'game8', '3 v 2')">
                  <h5 class="center-align white-text">3 v 2</h5>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="card-panel orange hoverable"  v-on:click="openGameModal($event, 'game9', '3 v 3')">
                  <h5 class="center-align white-text">3 v 3</h5>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

</template>

<script>
import GameModal from "@/components/admin/GameModal";
export default {
  name: "AdminNewScorecard",
  components: {GameModal},
  data() {
    return {
      currentModal: "",
      scorecard: {
        homeTeam: null,
        awayTeam: null,
        datePlayed: null,
        homePoints: null,
        awayPoints: null,
        homePlayers: {
          player1: null,
          player2: null,
          player3: null,
          player4: null,
          player5: null,
          player6: null,
        },
        awayPlayers: {
          player1: null,
          player2: null,
          player3: null,
          player4: null,
          player5: null,
          player6: null,
        },
        games: {
          game1: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game2: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game3: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game4: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game5: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game6: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game7: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game8: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,},
          game9: {home1: null, away1: null,home2: null, away2: null,home3: null, away3: null,}
        }
      }
    }
  },
  computed: {
    getTeams() {
      return this.$store.state.teams;
    }
  },
  methods: {
    openGameModal(event, gameId, title) {
      console.log("Open a game modal for " + gameId + ". Titled '" + title  + "'");
      let element = document.getElementById("game-modal");
      console.log(element);
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
  },
  mounted() {
    // Create the select boxes
  }
}
</script>

<style scoped>
.valign-wrapper {
  display: block;
}
</style>