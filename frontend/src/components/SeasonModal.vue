<template>
  <div :id="id" class="modal">
    <div class="modal-content">
      <h4>Available seasons</h4>
      <ul id="seasons" class="collection">
        <li v-for="season in seasons" :key="season.id">{{ season.slug }}</li>
      </ul>
    </div>
    <div class="modal-footer">
      <a class="modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
  </div>
</template>

<script>
export default {
  name: "SeasonModal",
  props: {
    id: String
  },
  data() {
    return {
      seasons: this.$store.state.seasons
    }
  },
  created() {
    this.$store.dispatch('loadSeasons');
  }
}
</script>

<style scoped>

</style>