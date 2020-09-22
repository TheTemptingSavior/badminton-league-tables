<template>
  <div :id="id" class="modal">
    <div class="modal-content">
      <h4>Available seasons</h4>
      <ul id="seasons" class="collection">
        <a v-for="season in getSeasons" :key="season.id" class="collection-item modal-close" v-on:click="changeSeason(season.id)">
          {{ season.start }} to {{ season.end }}
        </a>
      </ul>
    </div>
    <div class="modal-footer">
      <a class="modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div>
</template>

<script>
import M from 'materialize-css'
import moment from 'moment'
export default {
  name: "SeasonModal",
  props: {
    id: String
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
      console.log("[SeasonModal] Changing season to " + sid);
      this.$emit('seasonChange', sid);
    }
  },
  created() {
    this.$store.dispatch('loadSeasons');
    let elem = document.getElementById("#" + this.id);
    M.Modal.init(elem)
  }
}
</script>

<style scoped>

</style>