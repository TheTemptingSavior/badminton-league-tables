<template>
  <v-container>
    <v-row>
      <v-col cols="12" xs="12" class="text-center">
        <h3 class="accent--text text-h3">Logged in successfully</h3>
      </v-col>
    </v-row>
    <v-divider />
    <p>
      Maybe some stats about the site??
    </p>
    <ul>
      <li>Number of teams</li>
      <li>Number of seasons</li>
      <li>Number of games played (total + this season)</li>
    </ul>
  </v-container>
</template>

<script>
export default {
name: "AdminHome",
}
</script>

<style scoped>

</style>