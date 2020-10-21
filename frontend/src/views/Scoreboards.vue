<template>
  <div class="scoreboards">
    <SeasonModal id="seasons-modal" @seasonChange="changeSeason" />
    <div class="container">
      <h2 class="title orange-text center">
        Current Scoreboard
        <br />
        <small>Season {{ currentSeason }}</small>
      </h2>
      <div class="container center-align">
        <button type="button" data-target="seasons-modal" class="btn waves-effect waves-light modal-trigger">
          More Seasons
        </button>
        &nbsp;<button class="btn waves-effect waves-light" v-on:click="refresh">Refresh</button>
      </div>
      <hr />
      <ScoreboardTable :data=currentScoreboard :error=error />
      <h6 v-if="error !== null" class="red-text center">{{ error }}</h6>
    </div>
    <br />
    <br />

  </div>
</template>

<script>
import ScoreboardTable from "@/components/ScoreboardTable";
import SeasonModal from "@/components/SeasonModal";

export default {
  name: "Scoreboards",
  components: {SeasonModal, ScoreboardTable},
  data() {
      return {
        error: null,
        scoreboardId: null
      }
  },
  computed: {
    currentScoreboard() {
      if (this.$store.state.scoreboards.current.length === 0 || this.$store.state.teams.length === 0) {
        this.setError("Failed loading scoreboard.");
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
      if (this.$store.state.scoreboards.current.length === 0 || this.$store.state.teams.length === 0) {
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
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadCurrentScoreboard');
  }
}
</script>

<style scoped>

</style>