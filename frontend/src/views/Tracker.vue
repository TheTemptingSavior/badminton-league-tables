<template>
  <v-container>
    <v-row >
      <v-col cols="12" xs="12" class="text-center">
        <h2 class="accent--text text-h2">Game Tracker</h2>
        <h5 class="text-center accent--text text-h5">{{ currentSeason }}</h5>
        <div class="text-center py-5">
          <SeasonModal @seasonChange="changeSeason"  />
        </div>
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
                <v-list-item v-for="played in team.played" :key="played.id" class="success" :to="'/scorecards/' + played.scorecard_id">
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
import SeasonModal from "@/components/SeasonModal";
export default {
  name: "Tracker",
  components: {SeasonModal},
  computed: {
    getTeams() {
      return this.$store.getters.getTeams;
    },
    currentSeason() {
      if (this.$store.state.currentLoaded) {
        return "Season " + this.$store.getters.getSeason.slug;
      } else {
        return "Season N/A"
      }
    },
    getTracker() {
      if (! this.$store.state.currentLoaded) {
        return [];
      }
      return this.$store.getters.getTracker;
    }
  },
  methods: {
    changeSeason(sid) {
      console.log("Changing season to " + sid);
      this.$store.dispatch('loadOther', {
        sid: sid
      });
    }
  },
  created() {
    this.$store.dispatch('loadCurrent');
  }
}
</script>

<style scoped>

</style>