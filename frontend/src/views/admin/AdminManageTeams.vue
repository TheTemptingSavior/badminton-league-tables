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
        <v-col cols="12" xs="12" sm="6" md="4" v-for="(team, key) in teams" :key="team.id">
          <v-card class="mx-auto" elevation="5" :id="'team-' + team.id">
            <v-card-text>
              <div>Team Name</div>
              <p class="display-1 text--primary">
                {{ team.name }}
              </p>
              <v-divider />
            </v-card-text>
            <v-card-actions>
              <TeamEditor :teamIndex="key++" />
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>

<script>
import Vue from "vue";
import TeamEditor from "../../components/admin/TeamEditor";
import {EventBus} from "../../plugins/event-bus";
import {isLoggedIn} from "../../helpers";

export default {
  name: "AdminManageTeams",
  components: {TeamEditor},
  computed: {
    teams() {
      return this.$store.state.teams
    }
  },
  methods: {
    // retireTeam(teamId) {
    //   console.log("Retiring team with ID: " + teamId);
    //   Vue.axios.put('/api/teams/' + teamId + '/retire').then((response) => {
    //     console.log(response);
    //   }).catch((error) => {
    //     EventBus.$emit("show-error", "Failed getting list of teams. Try refreshing the page");
    //     throw new Error(`API ${error}`);
    //   }).finally(() => {
    //     // Turn of the loading
    //   })
    // }
  },
  created() {
    if (! isLoggedIn(this.$store.getters.user)) {
      this.$router.push('/login');
    }
    this.$store.dispatch('loadTeams');
    this.$store.dispatch('loadCurrent');
  }
}
</script>

<style scoped>

</style>