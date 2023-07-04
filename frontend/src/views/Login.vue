<template>
  <div class="login center">
    <div class="section"></div>
    <div class="container">
      <h3 class="orange-text title">Login</h3>
      <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
        <form class="col s12">
          <div class='row'>
            <div class='col s12'></div>
          </div>
          <div class='row'>
            <div class='input-field col s12'>
              <input v-model="state.username" class='validate' type='text' name='username' id='username' />
              <label for='username'>Enter your username</label>
            </div>
          </div>
          <div class='row'>
            <div class='input-field col s12'>
              <input v-model="state.password" class='validate' type='password' name='password' id='password' />
              <label for='password'>Enter your password</label>
            </div>
            <label style='float: right;'>
              <a class='pink-text'><b>Forgot Password?</b></a>
            </label>
          </div>
          <br />
          <center>
            <div class='row'>
              <button type="button" v-on:click="login()" class="col s12 btn btn-large waves-effect waves-light orange">Login</button>
            </div>
          </center>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
export default {
  name: "Login",
  data() {
    return {
      state: {
        username: "",
        password: ""
      }
    }
  },
  methods: {
    login() {
      Vue.axios.post("/api/auth/login", {
        username: this.state.username, password: this.state.password
      }).then((response) => {
        if (response.data.token) {
          this.$store.dispatch('setToken', response.data);
          this.$router.push('/admin')
        }
      }).catch((error) => {
        console.log(error);
      })
    }
  }
}
</script>

<style scoped>

</style>