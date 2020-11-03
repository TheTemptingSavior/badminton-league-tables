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
            <template v-slot:item.actions="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
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
      // TODO: Redirect to an edit page instead
      console.log("Open up edit form for scorecard " + item.id);
      this.$router.push({
        name: 'AdminEditScorecard',
        params: {
          id: item.id
        }
      });
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