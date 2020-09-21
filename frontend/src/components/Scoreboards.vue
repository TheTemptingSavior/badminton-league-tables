<template>
  <div class="scoreboards">
    <div class="container">
      <h2 class="title orange-text center">Current Scoreboard</h2>
      <hr />
      <table class="striped responsive-table">
        <thead>
          <tr>
            <th>Team</th>
            <th>Played</th>
            <th>Points</th>
            <th>Wins</th>
            <th>Losses</th>
            <th>For</th>
            <th>Against</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in currentScoreboard" :key="row.id">
            <td>{{ row.name }}</td>
            <td>{{ row.played }}</td>
            <td>{{ row.points }}</td>
            <td>{{ row.wins }}</td>
            <td>{{ row.losses }}</td>
            <td>{{ row.for }}</td>
            <td>{{ row.against }}</td>
          </tr>
        </tbody>
      </table>
      <h6 v-if="error !== null" class="red-text center">{{ error }}</h6>
    </div>
  </div>
</template>

<script>
export default {
  name: "Scoreboards",
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