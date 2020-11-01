<template>
  <v-app id="app">
    <v-navigation-drawer app absolute left v-model="drawer" :permanent="$vuetify.breakpoint.mdAndUp" class="px-3 py-5">
      <!-- Side Navigation Drawer -->
      <NavBar />
    </v-navigation-drawer>

    <!-- Top bar -->
    <v-app-bar app color="blue" dark>
      <v-app-bar-nav-icon @click="drawer = !drawer" />
      <v-toolbar-title>League Tables</v-toolbar-title>
    </v-app-bar>

    <!-- Page content -->
    <v-main app>
      <router-view />
    </v-main>

    <v-footer app>
      <!-- Footer content -->
    </v-footer>
  </v-app>
</template>

<style>

</style>
<script>
import NavBar from "@/components/NavBar";
export default {
  components: { NavBar },
  data() {
    return {
      drawer: null,
    }
  }
}
</script>