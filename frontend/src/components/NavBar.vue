<template>
  <div class="nav">
    <nav class="light-blue lighten-1">
      <div class="nav-wrapper">
        <router-link to="/" class="brand-logo">League Tables</router-link>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down">
          <li v-bind:class="{ active: isHome }"><router-link to="/">Home</router-link></li>
          <li v-bind:class="{ active: isScoreboards }"><router-link to="/scoreboards">Scoreboards</router-link></li>
          <li v-bind:class="{ active: isTracker }"><router-link to="/tracker">Tracker</router-link></li>

          <li v-if="isLoggedIn" v-bind:class="{ adminActive: isAdmin }"><router-link to="/admin">Admin</router-link></li>
          <li v-if="!isLoggedIn" v-bind:class="{ active: isLogin }"><router-link to="/login">Login</router-link></li>
          <li v-else v-on:click="logout"><router-link to="/">Logout</router-link></li>
        </ul>
      </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
      <li v-bind:class="{ active: isHome }"><router-link to="/">Home</router-link></li>
      <li v-bind:class="{ active: isScoreboards }"><router-link to="/scoreboards">Scoreboards</router-link></li>
      <li v-bind:class="{ active: isTracker }"><router-link to="/tracker">Tracker</router-link></li>

      <li v-if="isLoggedIn" v-bind:class="{ active: isAdmin }"><router-link to="/admin">Admin</router-link></li>

      <li v-if="!isLoggedIn" v-bind:class="{ active: isLogin }"><router-link to="/login">Login</router-link></li>
      <li v-else v-on:click="logout"><router-link to="/">Logout</router-link></li>
    </ul>

    <AdminNavBar v-if="isLoggedIn" />
  </div>
</template>

<script>
import AdminNavBar from "@/components/admin/AdminNavBar";
export default {
  name: "NavBar",
  components: {AdminNavBar},
  data() {
    return {
      dropdownName: "admin-dropdown"
    }
  },
  computed: {
    isHome() {
      return this.$route.name === 'Home';
    },
    isScoreboards() {
      return this.$route.name === 'Scoreboards';
    },
    isTracker() {
      return this.$route.name === 'Tracker';
    },
    isLogin() {
      return this.$route.name === 'Login';
    },
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
    }
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
.adminActive {
  background: #039be5;
}
</style>