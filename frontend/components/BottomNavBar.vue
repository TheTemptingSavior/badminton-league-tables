<template>
  <v-footer app color="indigo">
      <span class="white--text">
        &copy; {{ new Date().getFullYear() }}
      </span>
  </v-footer>
</template>

<script>
export default {
  name: 'BottomNavBar',
  props: ['drawer']
}
</script>

<style scoped>

</style>
