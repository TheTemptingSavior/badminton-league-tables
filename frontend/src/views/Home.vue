<template>
  <div class="home">
    <div class="section no-pad-bot" id="index-banner">
      <div class="container">
        <br><br>
        <h1 class="header center orange-text">Badminton League Tables</h1>
        <div class="row center">
          <h5 class="header col s12 light">
            Rutland and Stamford League
          </h5>
        </div>
        <div class="row center">
          <router-link to="/scoreboards" class="btn-large waves-effect waves-light orange">
            View Scoreboards
          </router-link>
        </div>
        <br><br>
        <div class="container">
          <div class="section">
            <!--   Icon Section   -->
            <div class="row">
              <div class="col s12 m4">
                <div class="icon-block">
                  <h2 class="center light-blue-text"><i class="material-icons">flash_on</i></h2>
                  <h5 class="center">Played Game Tracker</h5>
                  <p class="light">
                    Track the games have and haven't been played.
                  </p>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="icon-block">
                  <h2 class="center light-blue-text"><i class="material-icons">group</i></h2>
                  <h5 class="center">Up-to-date Scoreboards</h5>
                  <p class="light">
                    Scoreboards are updated everytime a new game is played
                  </p>
                </div>
              </div>
              <div class="col s12 m4">
                <div class="icon-block">
                  <h2 class="center light-blue-text"><i class="material-icons">settings</i></h2>
                  <h5 class="center">Item No. 3</h5>
                  <p class="light">
                    Just a few words here
                  </p>
                </div>
              </div>
            </div>

          </div>
          <br><br>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// @ is an alias to /src

export default {
  name: 'Home',
  components: {

  }
}
</script>
