<template>
  <div class="scoreboards">
    <SeasonModal id="seasons-modal" />
    <div class="container">
      <h2 class="title orange-text center">
        Current Scoreboard
        <br />
      </h2>
      <div class="container center-align">
        <a v-on="launchModal()" class="btn waves-effect waves-light">More Seasons</a>
        &nbsp;
        <a class="btn waves-effect waves-light">Refresh</a>
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
import M from 'materialize-css'

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
    launchModal() {
      M.AutoInit();
      let instance = document.getElementById('#seasons-modal');
      console.log(instance);
      // M.Modal.getInstance(instance).open();
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadScoreboard');
  }
}
</script>

<style scoped>

</style>