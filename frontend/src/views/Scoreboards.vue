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
      <Loader :showing="isLoading"/>
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
    isLoading() {
      return this.$store.state.loading;
    },
    currentScoreboard() {
      if (this.$store.state.scoreboards.current.data === undefined || this.$store.state.teams.length === 0) {
        if (this.isLoading) {
          console.log("Not showing 'Failed' message as still loading");
        } else {
          this.setError("Failed loading scoreboard.");
          console.log("Failed to load scoreboard and ")
        }
        return []
      }
      this.setError(null);
      return this.$store.state.scoreboards.current.data.map(row => {
        return {
          ...row,
          name: this.$store.state.teams.filter(x => x.id === row.team)[0].name
        }
      });
    },
    currentSeason() {
      if (this.$store.state.scoreboards.current.season === undefined || this.$store.state.teams.length === 0) {
        return "N/A";
      } else {
        return this.$store.state.scoreboards.current.slug;
      }
    }
  },
  methods: {
    setError(msg) {
      this.error = msg;
    },
    changeSeason(sid) {
      this.$store.dispatch('loadScoreboard', {
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
    this.$store.dispatch('setLoading');
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadCurrentScoreboard');
  }
}
</script>

<style scoped>

</style>