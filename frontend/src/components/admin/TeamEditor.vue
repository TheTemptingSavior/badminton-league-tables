<template>
  <v-dialog v-model="dialog" max-width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn color="primary" text v-bind="attrs" v-on="on">
        Edit
      </v-btn>
    </template>
    <v-card :loading="isUpdating">
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
          <v-checkbox
              v-for="a in checkboxes"
              v-bind:key="a.id"
              v-model="a.active"
              :label="a.slug"
          />
        </v-form>
      </v-card-text>
      <v-card-text v-else>
        Loading the team information
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text color="accent" v-on:click="saveForm">Update</v-btn>
        <v-btn text color="error" @click="dialog = false">
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
      isUpdating: false,
      nameRules: [
          v => !!v || 'Team name is required',
          v => (v && v.length < 255) || 'Team name is too long',
          v => (v && 2 < v.length) || 'Team name is too short',
      ],
      checkboxes: []
    }
  },
  computed: {
    team() {
      return this.$store.getters.getTeam(this.teamIndex);
    },
  },
  methods: {
    calculateCheckboxes(activeSeasons) {
      let seasonIndex, season;
      let d = [];
      for(seasonIndex in this.$store.state.seasons) {
        season = this.$store.state.seasons[seasonIndex];
        if (activeSeasons.find(({ id }) => id === season.id) !== undefined) {
          d.push({sid: season.id, slug: season.slug, active: true})
        } else {
          d.push({sid: season.id, slug: season.slug, active: false})
        }
      }
      return d;
    },
    saveForm() {
      this.isUpdating = true;
      let data = {
        data: this.checkboxes.map(function(cb) {
          return {
            active: cb.active,
            season: cb.sid
          }
        })
      };
      const headers = {
        'Content-Type' : 'application/json',
        'Authorization': 'Bearer ' + this.$store.getters.token
      }
      Vue.axios.put('/api/teams/' + this.team.id + '/retire', data, { headers }).then((response) => {
        console.log(response);
      }).catch((error) => {
        console.log(error)
      }).finally(() => {
        this.isUpdating = false;
      })
    }
  },
  created() {
    let teamId = this.team.id;
    Vue.axios.get('/api/teams/' + teamId + '/seasons').then((response) => {
      this.checkboxes = this.calculateCheckboxes(response.data.active);
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