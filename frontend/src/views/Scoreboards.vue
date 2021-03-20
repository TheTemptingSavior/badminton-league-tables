<template>
  <div class="scoreboards">
    <v-container>
      <h2 class="text-center accent--text text-h2">
        Current Scoreboard
      </h2>
      <h5 class="text-center accent--text text-h5">Season {{ currentSeason }}</h5>
      <div class="text-center py-5">
        <SeasonModal id="seasons-model" @seasonChange="changeSeason" />
      </div>
      <Loader :showing="!isLoaded"/>
      <v-divider />
      <ScoreboardTable :data=currentScoreboard :error=error />
      <h6 v-if="error !== null" class="text-center red--text text-h6">{{ error }}</h6>
    </v-container>
    <br />
    <br />

  </div>
</template>

<script>
import ScoreboardTable from "@/components/ScoreboardTable";
import SeasonModal from "@/components/SeasonModal";
import Loader from "../components/Loader";

export default {
  name: "Scoreboards",
  components: {Loader, SeasonModal, ScoreboardTable},
  data() {
      return {
        error: null,
        scoreboardId: null,
      }
  },
  computed: {
    isLoaded() {
      return this.$store.state.currentLoaded;
    },
    currentScoreboard() {
      if (! this.$store.state.currentLoaded) {
        return []
      }
      this.setError(null);
      return this.$store.getters.getScoreboard.map(row => {
        return {
          ...row,
          name: this.$store.getters.getTeams.filter(x => x.id === row.team)[0].name
        }
      });
    },
    currentSeason() {
      if (! this.$store.state.currentLoaded) {
        return "N/A";
      } else {
        return this.$store.getters.getSeason.slug;
      }
    }
  },
  methods: {
    setError(msg) {
      this.error = msg;
    },
    changeSeason(sid) {
      this.$store.dispatch('loadOther', {
        sid: sid
      });
    },
    refresh() {
      this.$store.dispatch('loadCurrentScoreboard');
    },
    showDialog() {

    }
  },
  created() {
    this.$store.dispatch('loadCurrent');
  }
}
</script>

<style scoped>

</style>