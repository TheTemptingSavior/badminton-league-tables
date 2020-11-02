<template>
  <v-simple-table class="striped responsive-table">
    <template v-slot:default>
      <thead>
        <tr>
          <th>Team</th><th>Played</th><th>Points</th><th>Wins</th><th>Losses</th><th>For</th><th>Against</th>
        </tr>
      </thead>
      <tbody>
      <tr v-for="row in data" :key="row.id">
        <td>{{ row.name }}</td>
        <td>{{ row.played }}</td>
        <td>{{ row.points }}</td>
        <td>{{ row.wins }}</td>
        <td>{{ row.losses }}</td>
        <td>{{ row.for }}</td>
        <td>{{ row.against }}</td>
      </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
export default {
  name: "ScoreboardTable",
  props: {
    data: Array,
    error: String
  }
}
</script>

<style scoped>

</style>