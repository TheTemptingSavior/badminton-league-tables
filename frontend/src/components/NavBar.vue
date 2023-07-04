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
          <li v-bind:class="{ active: isLogin }"><router-link to="/login">Login</router-link></li>
        </ul>
      </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
      <li><router-link to="/">Home</router-link></li>
    </ul>

  </div>
</template>

<script>
import M from 'materialize-css';

export default {
  name: "NavBar",
  mounted() {
    M.AutoInit();
    console.log(this.$route.name);
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
    }
  }
}
</script>

<style scoped>

</style>