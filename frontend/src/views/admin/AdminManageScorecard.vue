<template>
  <v-container>
    <h3 class="accent--text text-center text-h3">Manage Scorecards</h3>
    <br />
    <v-divider />

    <v-select
        v-if="seasons !== null"
        v-model="seasonId"
        :items="seasons"
        item-text="slug"
        item-value="id"
        label="Season"
        outlined
        v-on:change="seasonChange"
    ></v-select>
    <v-row v-if="seasonId !== null">
      <v-col cols="12" xs="12" sm="12" md="12" lg="10" offset-lg="1">
        <div class="text-center">
          <v-data-table
              dense
              :loading="loading"
              :headers="table.headers"
              :items="getScorecardList"
              class="elevation-1"
          >
            <template v-slot:top>
              <v-dialog v-model="deleteDialog" max-width="500px">
                <v-card>
                  <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                  <v-card-subtitle class="subtitle-1 red--text">This action is irreversible</v-card-subtitle>
                  <v-divider />
                  <v-card-text>
                    <v-row v-if="deleteError !== null">
                      <p class="red--text text-center">{{ deleteError }}</p>
                    </v-row>
                    <v-row v-if="toDelete !== null">
                      <v-col cols="12" xs="12" sm="5" class="text-center">{{ toDelete.homeTeam }}</v-col>
                      <v-col cols="12" xs="12" sm="2" class="text-center">vs</v-col>
                      <v-col cols="12" xs="12" sm="5" class="text-center">{{ toDelete.awayTeam }}</v-col>
                      <v-col cols="12" xs="12" class="text-center">that was played on {{ toDelete.datePlayed }}</v-col>
                    </v-row>
                    <v-row v-else>
                      <v-col cols="12" xs="12" class="red--text font-weight-bold">
                        Something went wrong. Please refresh the page
                      </v-col>
                    </v-row>
                    <v-checkbox
                        v-model="deleteCheckBox"
                        label="`Are you sure?`"
                        required
                    ></v-checkbox>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="confirmDelete">OK</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)">
                mdi-delete
              </v-icon>
            </template>
          </v-data-table>
        </div>
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col cols="12" xs="12" class="text-center">
        <h5 class="red--text text-h5 text-center">Please select a season</h5>
      </v-col>
    </v-row>

  </v-container>
</template>

<script>
import Vue from 'vue'
export default {
  name: "AdminManageScorecard",
  data() {
    return {
      seasonId: null,
      deleteDialog: false,
      toDelete: null,
      deleteError: null,
      deleteCheckBox: false,
      table: {
        headers: [
          {
            text: "ID",
            align: 'start',
            sortable: true,
            value: 'id'
          },
          {
            text: 'Home Team',
            align: 'start',
            sortable: true,
            value: 'homeTeam',
          },
          {
            text: 'Away Team',
            align: 'start',
            sortable: true,
            value: 'awayTeam',
          },
          {
            text: 'Date Played',
            align: 'start',
            sortable: true,
            value: 'datePlayed'
          },
          {
            text: 'Home Points',
            align: 'start',
            value: 'homePoints'
          },
          {
            text: 'Away Points',
            align: 'start',
            value: 'awayPoints'
          },
          {
            text: 'Actions',
            value: 'actions',
            sortable: false
          }
        ]
      },
      pagination: {
        page: null,
        perPage: null,
        nextPage: null,
        previousPage: null,
      },
      scorecards: [],
      loading: false,
    }
  },
  computed: {
    seasons() {
      let sorted = this.$store.state.seasons;
      sorted.sort((a, b) => {
        return a.slug < b.slug;
      });

      return sorted;
    },
    getScorecardList() {
      return this.scorecards.map(x => {
        return {
          id: x['id'],
          homeTeam: this.$store.state.teams.filter(t => { return t.id === x['home_team']})[0].name,
          awayTeam: this.$store.state.teams.filter(t => { return t.id === x['away_team']})[0].name,
          datePlayed: x['date_played'],
          homePoints: x['home_points'],
          awayPoints: x['away_points']
        }
      })
    }
  },
  methods: {
    seasonChange() {
      this.loading = true;
      console.log("Making request for scorecard in season " + this.seasonId);
      Vue.axios.get('/api/seasons/' + this.seasonId + '/scorecards?per_page=99').then((response) => {
        this.scorecards = response.data;
      }).catch((error) => {
        throw new Error(`API Error: ${error}`);
      }).finally(() => {
        this.loading = false;
      })
    },
    editItem(item) {
      console.log("Open up edit form for scorecard " + item.id);
      this.$router.push({
        name: 'AdminEditScorecard',
        params: {
          id: item.id
        }
      });
    },
    deleteItem(item) {
      console.log("Request to delete scorecard: " + item.id);
      this.toDelete = item;
      this.deleteDialog = true;
    },
    closeDelete() {
      this.deleteDialog = false;
      this.toDelete = null;
    },
    confirmDelete() {
      if (!this.deleteCheckBox) {
        // TODO: Find a more elegant way to do this like highlight the checkbox
        alert("You must tick the 'Are you sure?' checkbox before proceeding");
        return;
      }
      console.log("Item deleted");
      Vue.axios.delete(
          '/api/scorecards/' + this.toDelete.id,
          {headers: {Authorization: "Bearer " + this.$store.getters.token}}
      ).then((response) => {
        console.log("Scorecard deleted: " + response.data);
        this.scorecards = this.scorecards.filter(x => x['id'] !== this.toDelete.id)
        this.toDelete = null;
        this.deleteDialog = false;
      }).catch((error) => {
        this.deleteError = "Failed deleting the scorecard.";
        throw new Error(`API Error: ${error}`);
      })
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadSeasons');
  }
}
</script>

<style scoped>

</style>