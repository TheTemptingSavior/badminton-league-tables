<template>
  <v-app id="app">
    <v-snackbar v-model="snackbar.open" :timeout="snackbar.timeout" color="error">
      <span class="font-weight-bold">{{ snackbar.text }}</span>
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>

    <v-navigation-drawer app absolute left v-model="drawer" :permanent="$vuetify.breakpoint.mdAndUp" class="px-3 py-5">
      <!-- Side Navigation Drawer -->
      <NavBar />
    </v-navigation-drawer>

    <!-- Top bar -->
    <v-app-bar app color="primary" dark>
      <v-app-bar-nav-icon @click="drawer = !drawer" />
      <v-toolbar-title>League Tables</v-toolbar-title>
      <v-spacer />
      <SeasonModal id="seasons-model" @seasonChange="changeSeason" />
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
import { EventBus } from "@/plugins/event-bus";
import SeasonModal from "./components/SeasonModal";

export default {
  components: {SeasonModal, NavBar },
  data() {
    return {
      drawer: null,
      snackbar: {
        open: false,
        text: null,
        timeout: 5000
      },
    }
  },
  methods: {
    showError(message) {
      this.snackbar.text = message;
      this.snackbar.open = true;
    },
    changeSeason(sid) {
      this.$store.dispatch('loadOther', {
        sid: sid
      });
    },
  },
  mounted() {
    EventBus.$on("show-error", this.showError);
  }
}
</script>