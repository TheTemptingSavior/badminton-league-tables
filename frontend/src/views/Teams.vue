<template>
  <div id="teams">
    <v-row>
      <v-col cols="12" xs="12" class="text-center">
        <h2 class="text-center accent--text text-h2">
          League Teams
        </h2>
        <h5 class="text-center accent--text text-h5">{{ currentSeason }}</h5>
        <div class="text-center py-5">
          <SeasonModal @seasonChange="changeSeason"  />
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>

export default {
  name: 'Teams',
  computed: {
    seasonTeams() {
      return [];
    },
    currentSeason() {
      if (this.$store.state.tracker.current.season !== undefined) {
        return "Season " + this.$store.state.tracker.current.season.slug;
      } else {
        return "Season N/A"
      }
    },
  },
  methods: {
    changeSeason(sid) {
      console.log("Changing season to " + sid);
      this.$store.dispatch('loadSeasonTeams', {
        sid: sid
      });
    }
  },
  created() {
    // TODO: Implement this method to get an object back containing the
    //       teams active in the current season
    this.$store.dispatch('loadSeasonTeams');
  }
}
</script>
