<template>
  <v-app>
    <!-- Navigation drawer -->
    <TopNavBar drawer=drawer />

    <!-- App bar -->
    <v-app-bar app color="indigo" dark>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>Badminton League Tables</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn tile outlined rounded color="">
        <v-icon left>mdi-login</v-icon> Login
      </v-btn>
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main>
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view />
        <Login />
      </v-container>
    </v-main>

    <BottomNavBar />
  </v-app>
</template>

<script>

import BottomNavBar from '~/components/BottomNavBar'
import Login from '~/components/Login'
import TopNavBar from '~/components/TopNavBar'
export default {
  components: { TopNavBar, Login, BottomNavBar },
  data: () => ({
    drawer: true
  })
}
</script>
