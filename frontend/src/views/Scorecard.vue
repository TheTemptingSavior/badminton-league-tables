<template>
  <v-container>
    <h2 class="accent--text text-h2 text-center">Scorecard</h2>
    <v-text-field v-if="loading"
        color="primary"
        loading
        disabled
    ></v-text-field>
    <v-divider v-else />

    <div v-if="scorecard !== null">
    <v-row>
      <v-col cols="12" xs="12" sm="12" md="6">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="overline mb-4">
                HOME TEAM
              </div>
              <v-list-item-title class="headline mb-1">
                {{ scorecard['home_team_data']['name'] }}
              </v-list-item-title>
              <v-list-item-subtitle>Points: {{ scorecard['home_points'] }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-card-text>
            <div class="text--primary">
              <v-list>
                <v-subheader>PLAYERS</v-subheader>
                <v-list-item-group>
                  <v-list-item v-for="(player, i) in homePlayers" :key="i">
                    <v-list-item-icon>
                      <v-icon>[{{ i + 1 }}]</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title v-if="player">{{ player }}</v-list-item-title>
                      <v-list-item-title v-else>Not specified</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" xs="12" sm="6">
        <v-card class="mx-auto" outlined>
          <v-list-item three-line>
            <v-list-item-content>
              <div class="overline mb-4">
                AWAY TEAM
              </div>
              <v-list-item-title class="headline mb-1">
                {{ scorecard['away_team_data']['name'] }}
              </v-list-item-title>
              <v-list-item-subtitle>Points: {{ scorecard['away_points'] }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-card-text>
            <div class="text--primary">
              <v-list>
                <v-subheader>PLAYERS</v-subheader>
                <v-list-item-group>
                  <v-list-item v-for="(player, i) in awayPlayers" :key="i">
                    <v-list-item-icon>
                      <v-icon>[{{ i + 1 }}]</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title v-if="player">{{ player }}</v-list-item-title>
                      <v-list-item-title v-else>Not specified</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

      <v-row justify="center">
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 1 vs Pair 1</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                    <tr>
                      <td>
                        {{ scorecard['game_one_v_one_home_one'] === null ? "N/A" : scorecard['game_one_v_one_home_one'] }}
                      </td>
                      <td>
                        {{ scorecard['game_one_v_one_away_one'] === null ? "N/A" : scorecard['game_one_v_one_away_one'] }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ scorecard['game_one_v_one_home_two'] === null ? "N/A" : scorecard['game_one_v_one_home_two'] }}
                      </td>
                      <td>
                        {{ scorecard['game_one_v_one_away_two'] === null ? "N/A" : scorecard['game_one_v_one_away_two'] }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ scorecard['game_one_v_one_home_three'] === null ? "N/A" : scorecard['game_one_v_one_home_three'] }}
                      </td>
                      <td>
                        {{ scorecard['game_one_v_one_away_three'] === null ? "N/A" : scorecard['game_one_v_one_away_three'] }}
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 1 vs Pair 2</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_two_home_one'] === null ? "N/A" : scorecard['game_one_v_two_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_two_away_one'] === null ? "N/A" : scorecard['game_one_v_two_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_two_home_two'] === null ? "N/A" : scorecard['game_one_v_two_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_two_away_two'] === null ? "N/A" : scorecard['game_one_v_two_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_two_home_three'] === null ? "N/A" : scorecard['game_one_v_two_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_two_away_three'] === null ? "N/A" : scorecard['game_one_v_two_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 1 vs Pair 3</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_three_home_one'] === null ? "N/A" : scorecard['game_one_v_three_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_three_away_one'] === null ? "N/A" : scorecard['game_one_v_three_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_three_home_two'] === null ? "N/A" : scorecard['game_one_v_three_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_three_away_two'] === null ? "N/A" : scorecard['game_one_v_three_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_one_v_three_home_three'] === null ? "N/A" : scorecard['game_one_v_three_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_one_v_three_away_three'] === null ? "N/A" : scorecard['game_one_v_three_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row justify="center">
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 2 vs Pair 1</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_one_home_one'] === null ? "N/A" : scorecard['game_two_v_one_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_one_away_one'] === null ? "N/A" : scorecard['game_two_v_one_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_one_home_two'] === null ? "N/A" : scorecard['game_two_v_one_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_one_away_two'] === null ? "N/A" : scorecard['game_two_v_one_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_one_home_three'] === null ? "N/A" : scorecard['game_two_v_one_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_one_away_three'] === null ? "N/A" : scorecard['game_two_v_one_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 2 vs Pair 2</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_two_home_one'] === null ? "N/A" : scorecard['game_two_v_two_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_two_away_one'] === null ? "N/A" : scorecard['game_two_v_two_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_two_home_two'] === null ? "N/A" : scorecard['game_two_v_two_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_two_away_two'] === null ? "N/A" : scorecard['game_two_v_two_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_two_home_three'] === null ? "N/A" : scorecard['game_two_v_two_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_two_away_three'] === null ? "N/A" : scorecard['game_two_v_two_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 2 vs Pair 3</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_three_home_one'] === null ? "N/A" : scorecard['game_two_v_three_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_three_away_one'] === null ? "N/A" : scorecard['game_two_v_three_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_three_home_two'] === null ? "N/A" : scorecard['game_two_v_three_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_three_away_two'] === null ? "N/A" : scorecard['game_two_v_three_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_two_v_three_home_three'] === null ? "N/A" : scorecard['game_two_v_three_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_two_v_three_away_three'] === null ? "N/A" : scorecard['game_two_v_three_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <v-row justify="center">
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 3 vs Pair 1</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_one_home_one'] === null ? "N/A" : scorecard['game_three_v_one_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_one_away_one'] === null ? "N/A" : scorecard['game_three_v_one_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_one_home_two'] === null ? "N/A" : scorecard['game_three_v_one_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_one_away_two'] === null ? "N/A" : scorecard['game_three_v_one_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_one_home_three'] === null ? "N/A" : scorecard['game_three_v_one_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_one_away_three'] === null ? "N/A" : scorecard['game_three_v_one_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 3 vs Pair 2</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_two_home_one'] === null ? "N/A" : scorecard['game_three_v_two_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_two_away_one'] === null ? "N/A" : scorecard['game_three_v_two_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_two_home_two'] === null ? "N/A" : scorecard['game_three_v_two_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_two_away_two'] === null ? "N/A" : scorecard['game_three_v_two_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_two_home_three'] === null ? "N/A" : scorecard['game_three_v_two_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_two_away_three'] === null ? "N/A" : scorecard['game_three_v_two_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col xs="12" sm="12" md="4">
          <v-card elevation="4" outlined>
            <v-card-text>
              <div class="text-center text-h5">Pair 3 vs Pair 3</div>
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th class="text-center">Home</th>
                    <th class="text-center">Away</th>
                  </tr>
                  </thead>
                  <tbody class="text-center">
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_three_home_one'] === null ? "N/A" : scorecard['game_three_v_three_home_one'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_three_away_one'] === null ? "N/A" : scorecard['game_three_v_three_away_one'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_three_home_two'] === null ? "N/A" : scorecard['game_three_v_three_home_two'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_three_away_two'] === null ? "N/A" : scorecard['game_three_v_three_away_two'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{ scorecard['game_three_v_three_home_three'] === null ? "N/A" : scorecard['game_three_v_three_home_three'] }}
                    </td>
                    <td>
                      {{ scorecard['game_three_v_three_away_three'] === null ? "N/A" : scorecard['game_three_v_three_away_three'] }}
                    </td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>



    </div>

  </v-container>
</template>

<script>
import Vue from 'vue'
export default {
  name: "Scorecard",
  props: {
    scorecardId: String
  },
  data() {
    return {
      scorecard: null,
      loading: true,
      error: null,
    }
  },
  computed: {
    homePlayers() {
      if (this.scorecard !== null) {
        return [
          this.scorecard['home_player_1'],
          this.scorecard['home_player_2'],
          this.scorecard['home_player_3'],
          this.scorecard['home_player_4'],
          this.scorecard['home_player_5'],
          this.scorecard['home_player_6'],
        ]
      }
      return [];
    },
    awayPlayers() {
      if (this.scorecard !== null) {
        return [
          this.scorecard['away_player_1'],
          this.scorecard['away_player_2'],
          this.scorecard['away_player_3'],
          this.scorecard['away_player_4'],
          this.scorecard['away_player_5'],
          this.scorecard['away_player_6'],
        ]
      }
      return [];
    },
  },
  created() {
    this.$store.dispatch('loadTeams');
    Vue.axios.get('/api/scorecards/' + this.scorecardId).then((response) => {
      this.scorecard = response.data;
      this.loading = false;
    }).catch((error) => {
      this.scorecard = null;
      this.loading = null;
      this.error = "Failed to load scorecard";
      console.log(error);
    })
  }
}
</script>

<style scoped>

</style>