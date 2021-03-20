<template>
  <div id="teams">
    <v-row>
      <v-col cols="12" xs="12" class="text-center">
        <h2 class="text-center accent--text text-h2">
          League Teams
        </h2>
        <h5 class="text-center accent--text text-h5">{{ currentSeason }}</h5>
        <div class="text-center py-5">
          <SeasonModal @seasonChange="changeSeason"  />
        </div>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import SeasonModal from "@/components/SeasonModal";

export default {
  name: 'Teams',
  components: {SeasonModal},
  computed: {
    seasonTeams() {
      return [];
    },
    currentSeason() {
      if (this.$store.state.currentLoaded) {
        return "Season " + this.$store.getters.getSeason.slug;
      } else {
        return "Season N/A"
      }
    },
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
