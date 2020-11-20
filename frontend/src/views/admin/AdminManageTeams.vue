<template>
  <v-container>
    <v-row>
      <v-col cols="12" xs="12" class="text-center">
        <h3 class="accent--text text-h3">Manage Teams</h3>
      </v-col>
    </v-row>
    <v-divider />
    <v-row>
      <v-row justify="center">
        <v-col cols="12" xs="12" sm="6" md="4" v-for="team in teams" :key="team.id">
          <v-card class="mx-auto" elevation="5">
            <v-card-text>
              <div>Team Name</div>
              <p class="display-1 text--primary">
                {{ team.name }}
              </p>
              <v-divider />
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" text >
                Edit
              </v-btn>
              <v-btn color="black" text v-if="team.retired_on">
                Unretire
              </v-btn>
              <v-btn color="red" text v-else>
                Retire
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "AdminManageTeams",
  computed: {
    teams() {
      return this.$store.state.teams
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
  }
}
</script>

<style scoped>

</style>