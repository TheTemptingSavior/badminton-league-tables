<template>
  <div id="admin-new-scorecard" class="container">
    <h3 class="title center">New Scorecard</h3>
    <div id="new-card-form">
      <div class="row">
        <form class="col s12">
          <fieldset style="border: solid red 1px">
            <legend>Required Fields</legend>
            <div class="row">
              <div class="input-field col s12">
                <select id="home-team">
                  <option value="" disabled selected>Select home team</option>
                  <option v-for="team in getTeams" :key="team.id" :value="team.id">{{ team.name }}</option>
                </select>
                <span class="helper-text">Home Team</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <select id="away-team">
                  <option value="" disabled selected>Select away team</option>
                  <option v-for="team in getTeams" :key="team.id" :value="team.id">{{ team.name }}</option>
                </select>
                <span class="helper-text">Away Points</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input type="date" id="date-played" class="validate" />
                <span class="helper-text" data-error="wrong" data-success="right">Date Played</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6">
                <input type="number" id="home-points" class="validate" min="0" max="9" step="1" />
                <span class="helper-text">Home Points</span>
              </div>
              <div class="input-field col s6">
                <input type="number" id="away-points" class="validate" min="0" max="9" step="1" />
                <span class="helper-text">Away Points</span>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

</template>

<script>
import M from 'materialize-css'
export default {
  name: "AdminNewScorecard",
  computed: {
    getTeams() {
      return this.$store.state.teams;
    }
  },
  created() {
    this.$store.dispatch('loadTeams');
  },
  mounted() {
    // Render the home team select box
    let element = document.getElementById('home-team');
    M.FormSelect.init(element)

    // Render the away team select box
    element = document.getElementById('away-team');
    M.FormSelect.init(element)
  }
}
</script>

<style scoped>

</style>