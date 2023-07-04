<template>
  <div class="nav">
    <nav class="light-blue">
      <div class="nav-wrapper">
        <a href="#" data-target="mobile-demo" class="sidenav-trigger">
          <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down">
          <li v-bind:class="{ active: isHome }"><router-link to="/admin">Home</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">New Scorecard</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Delete Scorecard</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Teams</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Users</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Analytics</router-link></li>
          <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Help</router-link></li>
        </ul>
      </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">New Scorecard</router-link></li>
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Delete Scorecard</router-link></li>
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Teams</router-link></li>
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Users</router-link></li>
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Analytics</router-link></li>
      <li v-bind:class="{ active: isNewScorecard }"><router-link to="/">Help</router-link></li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "AdminNavBar",
  computed: {
    isHome() {
      return this.$route.name === 'AdminHome';
    },
    isNewScorecard() {
      return this.$route.name === 'AdminNewScorecard';
    }
  },
  methods: {

  }
}
</script>

<style scoped>

</style>