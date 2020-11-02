<template>
  <v-container class="home">
    <div id="page-header">
      <div class="text-center py-5">
        <h1 class="accent--text text-h2 font-weight-bolder">
          Badminton League Tables
        </h1>
        <h5 class="text-h5 pb-5">
          Rutland and Stamford League
        </h5>
        <v-btn
            to="/scoreboards"
            elevation="2"
            color="secondary"
            outlined
            rounded
            x-large>
          View Scorecards
        </v-btn>
      </div>
    </div>
    <div id="page-content" style="padding-top: 5%;">
      <v-row no-gutters>
        <v-col cols="12" sm="4" class="text-center">
          <v-icon x-large color="primary">mdi-grid</v-icon>
          <h5>Played Game Tracker</h5>
          <p class="light">
            Track the games have and haven't been played.
          </p>
        </v-col>
        <v-col cols="12" sm="4" class="text-center">
          <v-icon x-large color="primary">mdi-scoreboard</v-icon>
          <h5>Up-to-date Scoreboards</h5>
          <p class="light">
            Scoreboards are updated everytime a new game is played.
          </p>
        </v-col>
        <v-col cols="12" sm="4" class="text-center">
          <v-icon x-large color="primary">mdi-chart-bell-curve</v-icon>
          <h5>Team stats and Graphs</h5>
          <p class="light">
            Stats about games won and lost across seasons.
          </p>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>
<script>

export default {
  name: 'Home',
  components: {

  }
}
</script>
