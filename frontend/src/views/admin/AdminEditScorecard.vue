<template>
  <v-container id="admin-new-scorecard">
    <v-dialog v-model="popup.showing" width="500">
      <v-card>
        <v-card-title v-if="popup.success" class="headline success lighten">{{ popup.title }}</v-card-title>
        <v-card-title v-else class="headline error lighten">{{ popup.title }}</v-card-title>

        <v-card-text v-if="popup.success">
          <br />
          {{ popup.content }}
        </v-card-text>
        <v-card-text v-else>
          <v-list-item v-for="e in popup.content" v-bind:key="e">
            <v-list-item-content>
              {{ e }}
            </v-list-item-content>
          </v-list-item>
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer />
          <!-- TODO: This doesnt seem to close the popup -->
          <v-btn text @click="popup.showing = false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <h3 class="accent--text text-center text-h3">Edit Scorecard</h3>
    <v-divider />
    <v-form v-model="valid">
      <fieldset id="required-fieldset" style="border: solid 1px red">
        <legend>&nbsp;Required Fields</legend>
        <v-container>
          <v-row>
            <v-col cols="12" xs="12" sm="6">
              <v-select
                  v-model="scorecard.homeTeam"
                  :rules="rules.homeTeam"
                  :items="getTeams"
                  item-text="name"
                  item-value="id"
                  label="Home Team"
                  required
                  solo
              ></v-select>
            </v-col>
            <v-col cols="12" xs="12" sm="6">
              <v-select
                  v-model="scorecard.awayTeam"
                  :rules="rules.awayTeam"
                  :items="getTeams"
                  item-text="name"
                  item-value="id"
                  label="Away Team"
                  required
                  solo
              ></v-select>
            </v-col>
          </v-row>
          <v-row justify="center">
            <v-date-picker v-model="scorecard.datePlayed"></v-date-picker>
          </v-row>
          <v-row>
            <v-col cols="12" xs="12" sm="6">
              <v-slider
                  v-model="scorecard.homePoints"
                  color="accent"
                  label="Home Points"
                  hint="How many sets the home team won"
                  min="0"
                  max="9"
                  thumb-label="always"
              ></v-slider>
            </v-col>
            <v-col cols="12" xs="12" sm="6">
              <v-slider
                  v-model="scorecard.awayPoints"
                  color="accent"
                  label="Away Points"
                  hint="How many sets the away won"
                  min="0"
                  max="9"
                  thumb-label="always"
              ></v-slider>
            </v-col>
          </v-row>
        </v-container>
      </fieldset>
      <br />
      <fieldset id="player-fieldset">
        <legend>Players</legend>
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field v-model="scorecard.homePlayers.player1" label="Home Player 1"></v-text-field>
              <v-text-field v-model="scorecard.homePlayers.player2" label="Home Player 2"></v-text-field>
              <v-text-field v-model="scorecard.homePlayers.player3" label="Home Player 3"></v-text-field>
              <v-text-field v-model="scorecard.homePlayers.player4" label="Home Player 4"></v-text-field>
              <v-text-field v-model="scorecard.homePlayers.player5" label="Home Player 5"></v-text-field>
              <v-text-field v-model="scorecard.homePlayers.player6" label="Home Player 6"></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field v-model="scorecard.awayPlayers.player1" label="Away Player 1"></v-text-field>
              <v-text-field v-model="scorecard.awayPlayers.player2" label="Away Player 2"></v-text-field>
              <v-text-field v-model="scorecard.awayPlayers.player3" label="Away Player 3"></v-text-field>
              <v-text-field v-model="scorecard.awayPlayers.player4" label="Away Player 4"></v-text-field>
              <v-text-field v-model="scorecard.awayPlayers.player5" label="Away Player 5"></v-text-field>
              <v-text-field v-model="scorecard.awayPlayers.player6" label="Away Player 6"></v-text-field>
            </v-col>
          </v-row>
        </v-container>
      </fieldset>
      <br />
      <fieldset id="games-fieldset">
        <legend>Games</legend>
        <v-container>
          <v-row align="center" justify="center">
            <v-col cols="12" xs="12" md="4" class="text-center accent">
              <v-dialog v-model="dialogs.dialog1v1" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    1 vs 1
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 1 vs Away Pair 1</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game1.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog1v1 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog1v2" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    1 vs 2
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 1 vs Away Pair 2</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game2.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog1v2 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog1v3" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    1 vs 3
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 1 vs Away Pair 3</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game3.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog1v3 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog2v1" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    2 vs 1
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 2 vs Away Pair 1</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game4.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog2v1 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center accent">
              <v-dialog v-model="dialogs.dialog2v2" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    2 vs 2
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 2 vs Away Pair 2</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game5.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog2v2 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog2v3" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    2 vs 3
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 2 vs Away Pair 3</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game6.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog2v3 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog3v1" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    3 vs 1
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 3 vs Away Pair 1</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game7.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog3v1 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center primary">
              <v-dialog v-model="dialogs.dialog3v2" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    3 vs 2
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 3 vs Away Pair 2</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game8.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog3v2 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" xs="12" md="4" class="text-center accent">
              <v-dialog v-model="dialogs.dialog3v3" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="transparent" v-bind="attrs" v-on="on">
                    3 vs 3
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">Home Pair 3 vs Away Pair 3</span>
                  </v-card-title>
                  <v-divider />
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.home1" label="Game 1 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.away1" label="Game 1 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.home2" label="Game 2 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.away2" label="Game 2 Away"></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.home3" label="Game 3 Home"></v-text-field>
                        </v-col>
                        <v-col cols="12" xs="6" sm="6" md="6">
                          <v-text-field type="number" v-model="scorecard.games.game9.away3" label="Game 3 Away"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialogs.dialog3v3 = false">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
          </v-row>
        </v-container>
      </fieldset>
    </v-form>
    <br />
    <v-row justify="center">
      <v-btn color="primary" x-large elevation="3" v-on:click="updateScorecard">Update</v-btn>
    </v-row>
  </v-container>
</template>

<script>
import Vue from 'vue'
import {transformScorecard} from "../../helpers";

export default {
  name: "AdminEditScorecard",
  props: {
    id: Number
  },
  data() {
    return {
      loading: true,
      currentModal: "",
      valid: true,
      popup: {
        showing: false,
        title: "",
        content: "",
        success: true
      },
      dialogs: {
        dialog1v1: false,
        dialog1v2: false,
        dialog1v3: false,
        dialog2v1: false,
        dialog2v2: false,
        dialog2v3: false,
        dialog3v1: false,
        dialog3v2: false,
        dialog3v3: false,
      },
      rules: {
        homeTeam: [
          v => !!v || 'Home team is required',
          v => (v && v.value !== this.scorecard.awayTeam) || 'Home team cannot be the same as the away team'
        ],
        awayTeam: [
          v => !!v || 'Away team is required',
          v => (v && v.value !== this.scorecard.homeTeam) || 'Away team cannot be the same as the away team'
        ],
      },
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
      return this.$store.getters.allTeams;
    },
  },
  methods: {
    updateScorecard() {
      // TODO: Requires feedback to the user for if this works
      const headers = {
        'Content-Type' : 'application/json',
        'Authorization': 'Bearer ' + this.$store.getters.token
      }
      Vue.axios.put('/api/scorecards/' + this.id, transformScorecard(this.id, this.scorecard), { headers }).then((response) => {
        this.popup.title = "Successfully Updated"
        this.popup.success = true;
        if (response.data.warnings !== undefined && response.data.warnings !== null) {
          this.popup.content = response.data.warnings;
        } else {
          this.popup.content = "Updated successfully with no errors or warnings";
        }
      }).catch((error) => {
        console.log(error.response);
        this.popup.title = "Failed Updating"
        this.popup.content = error.response.data.errors;
        this.popup.success = false;
      }).finally(() => {
        this.popup.showing = true;
      });
    }
  },
  created() {
    this.loading = true;
  },
  mounted() {
    // Get the scorecard
    Vue.axios.get('/api/scorecards/' + this.id).then((response) => {
      this.scorecard.homeTeam = response.data['home_team'];
      this.scorecard.awayTeam = response.data['away_team'];
      this.scorecard.datePlayed = response.data['date_played'];
      this.scorecard.homePoints = response.data['home_points'];
      this.scorecard.awayPoints = response.data['away_points'];
      this.scorecard.homePlayers = {player1: response.data['home_player_1'], player2: response.data['home_player_2'], player3: response.data['home_player_3'], player4: response.data['home_player_4'], player5: response.data['home_player_5'], player6: response.data['home_player_6'],};
      this.scorecard.awayPlayers = {player1: response.data['away_player_1'], player2: response.data['away_player_2'], player3: response.data['away_player_3'], player4: response.data['away_player_4'], player5: response.data['away_player_5'], player6: response.data['away_player_6'],};
      this.scorecard.games.game1 = {home1: response.data['game_one_v_one_home_one'], home2: response.data['game_one_v_one_home_two'], home3: response.data['game_one_v_one_home_three'], away1: response.data['game_one_v_one_away_one'], away2: response.data['game_one_v_one_away_two'], away3: response.data['game_one_v_one_away_three']};
      this.scorecard.games.game2 = {home1: response.data['game_one_v_two_home_one'], home2: response.data['game_one_v_two_home_two'], home3: response.data['game_one_v_two_home_three'], away1: response.data['game_one_v_two_away_one'], away2: response.data['game_one_v_two_away_two'], away3: response.data['game_one_v_two_away_three']};
      this.scorecard.games.game2 = {home1: response.data['game_one_v_three_home_one'], home2: response.data['game_one_v_three_home_two'], home3: response.data['game_one_v_three_home_three'], away1: response.data['game_one_v_three_away_one'], away2: response.data['game_one_v_three_away_two'], away3: response.data['game_one_v_three_away_three']};
      this.scorecard.games.game4 = {home1: response.data['game_two_v_one_home_one'], home2: response.data['game_two_v_one_home_two'], home3: response.data['game_two_v_one_home_three'], away1: response.data['game_two_v_one_away_one'], away2: response.data['game_two_v_one_away_two'], away3: response.data['game_two_v_one_away_three']};
      this.scorecard.games.game5 = {home1: response.data['game_two_v_two_home_one'], home2: response.data['game_two_v_two_home_two'], home3: response.data['game_two_v_two_home_three'], away1: response.data['game_two_v_two_away_one'], away2: response.data['game_two_v_two_away_two'], away3: response.data['game_two_v_two_away_three']};
      this.scorecard.games.game6 = {home1: response.data['game_two_v_three_home_one'], home2: response.data['game_two_v_three_home_two'], home3: response.data['game_two_v_three_home_three'], away1: response.data['game_two_v_three_away_one'], away2: response.data['game_two_v_three_away_two'], away3: response.data['game_two_v_three_away_three']};
      this.scorecard.games.game7 = {home1: response.data['game_three_v_one_home_one'], home2: response.data['game_three_v_one_home_two'], home3: response.data['game_three_v_one_home_three'], away1: response.data['game_three_v_one_away_one'], away2: response.data['game_three_v_one_away_two'], away3: response.data['game_three_v_one_away_three']};
      this.scorecard.games.game8 = {home1: response.data['game_three_v_two_home_one'], home2: response.data['game_three_v_two_home_two'], home3: response.data['game_three_v_two_home_three'], away1: response.data['game_three_v_two_away_one'], away2: response.data['game_three_v_two_away_two'], away3: response.data['game_three_v_two_away_three']};
      this.scorecard.games.game9 = {home1: response.data['game_three_v_three_home_one'], home2: response.data['game_three_v_three_home_two'], home3: response.data['game_three_v_three_home_three'], away1: response.data['game_three_v_three_away_one'], away2: response.data['game_three_v_three_away_two'], away3: response.data['game_three_v_three_away_three']};
    }).catch((error) => {
      console.log("Failed getting the scorecard");
      throw new Error(`API Error: ${error}`);
    }).finally(() => {
      this.loading = false;
    })
  }
}
</script>

<style scoped>

</style>