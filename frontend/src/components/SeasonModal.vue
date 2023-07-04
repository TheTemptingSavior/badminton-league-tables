<template>
  <v-dialog v-model="dialog" max-width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          elevation="2"
          color="secondary"
          outlined
          rounded
          v-bind="attrs"
          v-on="on"
      >
        More Seasons
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        Available Seasons
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-list-item-group v-model="group">
          <v-list-item v-for="season in getSeasons" :key="season.id" @click="changeSeason(season.id)">
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ season.start }} to {{ season.end }}</v-list-item-title>
          </v-list-item>
        </v-list-item-group>
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
import moment from 'moment'
export default {
  name: "SeasonModal",
  props: {
    id: String
  },
  data() {
    return {
      group: null,
      dialog: false,
    }
  },
  computed: {
    getSeasons() {
      return this.$store.state.seasons.map(s => {
        return {
          slug: s.slug,
          start: moment(s.start).format("Do MMM YYYY"),
          end: moment(s.end).format("Do MMM YYYY"),
          id: s.id
        }
      })
    }
  },
  methods: {
    changeSeason(sid) {
      this.dialog = false;
      console.log("[SeasonModal] Changing season to " + sid);
      this.$emit('seasonChange', sid);
    }
  },
  created() {
    this.$store.dispatch('loadSeasons');
  }
}
</script>

<style scoped>

</style>