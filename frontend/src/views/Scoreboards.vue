<template>
  <div class="scoreboards">
    <SeasonModal id="seasons-modal" @seasonChange="changeSeason" />
    <div class="container">
      <h2 class="title orange-text center">
        Current Scoreboard
        <br />
      </h2>
      <div class="container center-align">
        <button type="button" data-target="seasons-modal" class="btn waves-effect waves-light modal-trigger">
          More Seasons
        </button>
        &nbsp;<a class="btn waves-effect waves-light">Refresh</a>
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
        error: null
      }
  },
  computed: {
    currentScoreboard() {
      if (this.$store.state.scoreboards.current.length === 0 || this.$store.state.teams.length === 0) {
        this.setError("Failed loading scoreboard.");
        return []
      }
      this.setError(null);
      return this.$store.state.scoreboards.current.map(row => {
        return {
          ...row,
          name: this.$store.state.teams.filter(x => x.id === row.team)[0].name
        }
      });
    }
  },
  methods: {
    setError(msg) {
      this.error = msg;
    },
    changeSeason(sid) {
      // TODO: Load a new scoreboard here
      // Potentially launch an Vuex action with an argument
      alert("About to show scoreboard for season of ID '" + sid + "'");
      console.log("[Scoreboards] Change of season to " + sid);
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