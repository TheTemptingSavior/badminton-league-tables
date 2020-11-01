<template>
  <div :id="id" class="modal">
    <div class="modal-content">
      <h4>Game: {{ title }}</h4>

    </div>
    <div class="modal-footer">
      <a class="modal-close waves-effect waves-green btn-flat">Save</a>
    </div>
  </div>
</template>

<script>
import M from 'materialize-css'
export default {
  name: "GameModal",
  props: {
    id: String,
    title: String
  },
  computed: {

  },
  methods: {

  },
  mounted() {
    let elem = document.getElementById("#" + this.id);
    M.Modal.init(elem);
  }
}
</script>

<style scoped>

</style>