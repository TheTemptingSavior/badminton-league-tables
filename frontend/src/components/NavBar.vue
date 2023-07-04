<template>
  <v-list nav dense>
    <div v-if="!$vuetify.breakpoint.mdAndUp"><br /><br /></div>
    <v-list-item-group v-model="group" active-class="orange--text text--accent-4">
      <v-list-item to="/">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Home</v-list-item-title>
      </v-list-item>
      <v-list-item to="/scoreboards">
        <v-list-item-icon>
          <v-icon>mdi-scoreboard</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Scorecards</v-list-item-title>
      </v-list-item>
      <v-list-item to="/tracker">
        <v-list-item-icon>
          <v-icon>mdi-grid</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Tracker</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin" v-if="isLoggedIn">
        <v-list-item-icon>
          <v-icon>mdi-shield-account</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Admin</v-list-item-title>
      </v-list-item>
      <v-list-item v-on:click="logout" v-if="isLoggedIn">
        <v-list-item-icon>
          <v-icon>mdi-lock-open</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Logout</v-list-item-title>
      </v-list-item>
      <v-list-item to="/login" v-if="!isLoggedIn">
        <v-list-item-icon>
          <v-icon>mdi-lock</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Login</v-list-item-title>
      </v-list-item>
    </v-list-item-group>

    <br />
    <v-divider />
    <br />

    <v-list-item-group v-model="adminGroup" v-if="isLoggedIn" active-class="orange--text text--accent-4">
      <v-list-item to="/admin/scorecards/new">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>New Scorecard</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin/scorecards/manage">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Manage Scorecards</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin/teams">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Teams</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin/analytics">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Analytics</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin/users">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Users</v-list-item-title>
      </v-list-item>
      <v-list-item to="/admin/help">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Help</v-list-item-title>
      </v-list-item>
    </v-list-item-group>
  </v-list>
</template>

<script>
export default {
  name: "NavBar",
  components: {},
  data() {
    return {
      group: null,
      adminGroup: null,
    }
  },
  computed: {
    isAdmin() {
      return this.$route.name.toLowerCase().startsWith('admin');
    },
    user() {
      return this.$store.state.user;
    },
    isLoggedIn() {
      let u = this.user;
      if (u.token !== null && u.expiresIn !== null && u.receiveTime !== null) {
        // All data is present so just assume logged in for no
        return Date.now() < (u.receiveTime + u.expiresIn)
      } else {
        return false;
      }
    }
  },
  methods: {
    logout() {
      // TODO: Redirect to the home page
      console.log("User logging out");
      this.$store.dispatch('logoutUser');
    },
  },
  created() {
    this.unwatch = this.$store.watch(
        (state, getters) => getters.user,
        (newValue, oldValue) => {
          console.log("User state was modified from:")
          console.log(oldValue);
          console.log("to")
          console.log(newValue);

          if (newValue.token !== null && newValue.expiresIn !== null && newValue.receiveTime !== null) {
            // All data is present so just assume logged in for now
            // TODO: Check if receiveTime + expiresIn < Date.now()
            this.isLoggedIn = true
          } else {
            this.isLoggedIn = false;
          }
        }
    );
  },
  beforeDestroy() {
    this.unwatch();
  }
}
</script>

<style scoped>

</style>