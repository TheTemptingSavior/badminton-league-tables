<template>
  <v-container id="login" class="text-center">
      <h3 class="accent--text text-h3 py-5">
        Login
      </h3>
    <v-row justify="center">
      <v-col cols="12" xs="12" sm="10" md="8" lg="6">
        <v-card outlined shaped elevation="5" :loading="isSubmitted">
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-card-text>
              <v-container>
                <v-row v-if="loginError !== null" justify="center">
                  <v-col cols="12" xs="12" sm="8">
                    <span class="red--text font-weight-bold">{{ loginError }}</span>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" xs="12">
                    <v-text-field
                        v-model="state.username"
                        :rules="rules.usernameRules"
                        label="Username"
                        prepend-icon="mdi-account"
                        required
                        autofocus
                        v-on:keyup.enter="validate"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" xs="12">
                    <v-text-field
                        v-model="state.password"
                        type="password"
                        :rules="rules.passwordRules"
                        label="Password"
                        prepend-icon="mdi-lock"
                        required
                        v-on:keyup.enter="validate"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" xs="12" class="text-right">
                    <router-link to="/forgotten-password">Forgotten Password?</router-link>
                  </v-col>
                </v-row>
                <v-row justify="center">
                  <v-col cols="12" xs="12">
                    <v-btn x-large color="accent text" :disabled="!valid" @click="validate" type="submit">Login</v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Vue from 'vue'
export default {
  name: "Login",
  data() {
    return {
      valid: false,
      loginError: null,
      isSubmitted: false,
      state: {
        username: "",
        password: ""
      },
      rules: {
        usernameRules: [
          v => !!v || 'Username is required',
          v => (v && v.length <= 50) || 'Username must be less than 50 characters',
          v => (v && 3 <= v.length) || 'Username must be greater than 3 characters'
        ],
        passwordRules: [
          v => !!v || 'Password is required',
        ]
      }
    }
  },
  methods: {
    login() {
      this.isSubmitted = true;
      Vue.axios.post("/api/auth/login", {
        username: this.state.username, password: this.state.password
      }).then((response) => {
        if (response.data.token) {
          this.$store.dispatch('loginUser', response.data);
          this.$router.push('/admin')
        }
      }).catch((error) => {
        console.log(error);
        this.loginError = "Incorrect username/password."
        this.isSubmitted = false;
      })
    },
    validate() {
      if (this.$refs.form.validate()) {
        this.login();
      }
    },
    reset () {
      this.$refs.form.reset()
    },
  }
}
</script>

<style scoped>

</style>