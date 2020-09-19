<template>
  <div>
  <v-app-bar app color="indigo" dark>
    <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
    <v-toolbar-title>Badminton League Tables</v-toolbar-title>

    <v-spacer></v-spacer>

    <v-btn tile outlined rounded color="">
      <v-icon left>mdi-login</v-icon> Login
    </v-btn>
  </v-app-bar>
  <v-navigation-drawer v-model="drawer" app>
    <v-list dense>
      <v-list-item link>
        <v-list-item-action>
          <v-icon>mdi-home</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Home</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link>
        <v-list-item-action>
          <v-icon>mdi-account-group</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Teams</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link>
        <v-list-item-action>
          <v-icon>mdi-calendar-clock</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Seasons</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link>
        <v-list-item-action>
          <v-icon>mdi-clipboard-list</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Tracker</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  name: 'TopNavBar',
  data: () => ({
    drawer: null
  })
}
</script>

<style scoped>

</style>
