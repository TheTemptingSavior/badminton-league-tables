<template>
  <v-container v-if="showing" >
    <p>Loading...</p>
    <v-img
        max-height="150"
        max-width="250"
        src="@/assets/spinner.gif" />
  </v-container>
</template>

<script>
export default {
  name: "Loader",
  props: {
    showing: Boolean
  }
}
</script>

<style scoped>

</style>