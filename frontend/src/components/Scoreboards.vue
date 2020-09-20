<template>
  <div class="scoreboards">
    <div class="container">
      <h2 class="title orange-text center">Current Scoreboard</h2>
      <hr />
      <table class="striped">
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
    </div>
  </div>
</template>

<script>
export default {
  name: "Scoreboards",
  computed: {
    currentScoreboard() {
      return this.$store.state.scoreboards.current.map(row => {
        return {
          ...row,
          name: this.$store.state.teams.filter(x => x.id === row.team)[0].name
        }
      });
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