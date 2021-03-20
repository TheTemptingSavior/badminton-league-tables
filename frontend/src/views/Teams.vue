<template>
  <v-container id="teams">
    <v-row>
      <v-col cols="12" xs="12" class="text-center">
        <h2 class="text-center accent--text text-h2">
          League Teams
        </h2>
        <h5 class="text-center accent--text text-h5">{{ currentSeason }}</h5>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" xs="12" sm="6" md="4" v-for="team in seasonTeams" :key="team.id">
        <v-card class="mx-auto" elevation="5" :id="'team-' + team.id">
          <v-card-text>
            <div>Team Name</div>
            <p class="display-1 text--primary">
              {{ team.name }}
            </p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  name: 'Teams',
  computed: {
    seasonTeams() {
      if (this.$store.state.currentLoaded) {
        return this.$store.getters.getTeams;
      } else {
        return [];
      }
    },
    currentSeason() {
      if (this.$store.state.currentLoaded) {
        return "Season " + this.$store.getters.getSeason.slug;
      } else {
        return "Season N/A"
      }
    },
  },
  methods: {
  },
  created() {
    this.$store.dispatch('loadCurrent');
  }
}
</script>
