<template>
  <v-container>
    <v-row >
      <v-col cols="12" xs="12" class="text-center">
        <h2 class="accent--text text-h2">Game Tracker</h2>
      </v-col>
    </v-row>
    <v-divider />
    <v-row justify="center">
      <v-col cols="12" xs="12" sm="6" md="4" v-for="team in getTracker" :key="team.id">
        <v-card class="mx-auto" elevation="5">
          <v-card-text>
            <div>Team Name</div>
            <p class="display-1 text--primary">
              {{ team.name }}
            </p>
            <v-divider />
            <v-list dense>
              <v-subheader>PLAYED</v-subheader>
              <v-list-item-group>
                <v-list-item v-for="played in team.played" :key="played.id" class="success">
                  <v-list-item-icon>
                    <v-icon>mdi-calendar-check</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>{{ played.name }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
              <v-subheader>NOT PLAYED</v-subheader>
              <v-list-item-group>
                <v-list-item v-for="played in team.not_played" :key="played.id" class="error">
                  <v-list-item-icon>
                    <v-icon>mdi-calendar-remove</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>{{ played.name }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "Tracker",
  computed: {
    getTeams() {
      return this.$store.state.teams;
    },
    getTracker() {
      if (this.$store.state.tracker.current.data === undefined) {
        return [];
      }
      return this.$store.state.tracker.current.data;
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadCurrentTracker');
  }
}
</script>

<style scoped>

</style>