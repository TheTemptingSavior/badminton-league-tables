<template>
  <div class="row">
    <div id="outer-col" class="col s12">
      <ul id="admin-navbar" class="tabs tabs-fixed-width light-blue darken-1">
        <li class="tab col l3 "><router-link to="/admin/scorecards/new" class="white-text" v-bind:class="{ active: isNewScorecard }">New Scorecard</router-link></li>
        <li class="tab col l3"><router-link to="/admin/scorecards/manage" class="white-text" v-bind:class="{ active: isManageScorecards }">Manage Scorecards</router-link></li>
        <li class="tab col l3"><router-link to="/admin/teams" class="white-text" v-bind:class="{ active: isTeams }">Teams</router-link></li>
        <li class="tab col l3"><router-link to="/admin/analytics" class="white-text" v-bind:class="{ active: isAnalytics }">Analytics</router-link></li>
        <li class="tab col l3"><router-link to="/admin/users" class="white-text" v-bind:class="{ active: isUsers }">Users</router-link></li>
        <li class="tab col l3"><router-link to="/admin/help" class="white-text" v-bind:class="{ active: isHelp }">Help</router-link></li>
      </ul>
    </div>

  </div>
</template>

<script>
export default {
  name: "AdminNavBar",
  computed: {
    isNewScorecard() {
      return this.$route.name === 'AdminNewScorecard';
    },
    isManageScorecards() {
      return this.$route.name === 'AdminManageScorecards';
    },
    isTeams() {
      return this.$route.name === 'AdminTeams';
    },
    isAnalytics() {
      return this.$route.name === 'AdminAnalytics';
    },
    isUsers() {
      return this.$route.name === 'AdminUsers';
    },
    isHelp() {
      return this.$route.name === 'AdminHelp';
    }
  }
}
</script>

<style scoped>
.active {
  border-bottom: 5px solid #FFFFFF;
}
#outer-col {
  padding: 0;
}
</style>