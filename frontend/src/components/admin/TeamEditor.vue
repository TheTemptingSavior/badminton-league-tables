<template>
  <v-dialog v-model="dialog" max-width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn color="primary" text v-bind="attrs" v-on="on">
        Edit
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        Edit Team
      </v-card-title>
      <v-divider />
      <v-card-text v-if="loaded">
        <br />
        <v-form :ref="'form'+team.id" v-model="valid" lazy-validation>
          <v-text-field
              v-model="team.name"
              :rules="nameRules"
              label="Team Name"
              required
          ></v-text-field>
          <v-divider />
          <br />
          <v-alert dense type="info" border="left">
            Select the seasons the team is active in. If the season
            has scorecards from that season. It will not be retired
          </v-alert>
          <pre>
// This will require a serverside check for if the team has
// any scorecards from the season that are to be
// retired from. If they do, they cannot be retired from it
For season in seasons:
  if team is active in season:
    checkbox checked
  else:
    checkbox unchecked
          </pre>
        </v-form>
      </v-card-text>
      <v-card-text v-else>
        Loading the team information
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="dialog = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Vue from 'vue';

export default {
  name: "TeamEditor",
  props: {
    teamIndex: Number
  },
  data() {
    return {
      dialog: false,
      valid: true,
      loaded: false,
      nameRules: [
          v => !!v || 'Team name is required',
          v => (v && v.length < 255) || 'Team name is too long',
          v => (v && 2 < v.length) || 'Team name is too short',
      ]
    }
  },
  computed: {
    team() {
      return this.$store.getters.getTeam(this.teamIndex);
    }
  },
  created() {
    let teamId = this.team.id;
    Vue.axios.get('/api/teams/' + teamId).then((response) => {
      console.log(response);
    }).catch((error) => {
      console.log("Failed getting the team information");
      console.log(error);
    }).finally(() => {
      this.loaded = true
    })
  }
}
</script>

<style scoped>

</style>