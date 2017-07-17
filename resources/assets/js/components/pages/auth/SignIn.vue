<template>
  <div>
    <v-card>
      <!-- Card Title -->
      <v-card-title class="blue-grey white--text">
        <div class="title">Sign In</div>
      </v-card-title>

      <!-- Card Text -->
      <v-card-text>
        <v-layout column>
          <!-- Email -->
          <v-flex>
            <v-text-field
              class="mb-0"
              name="email"
              label="Email"
              v-model="email"
            ></v-text-field>
          </v-flex>

          <!-- Password -->
          <v-flex>
            <v-text-field
              type="password"
              class="mb-0"
              name="password"
              label="Password"
              v-model="password"
            ></v-text-field>
          </v-flex>

          <v-flex>
            <!-- {{error}} -->
          </v-flex>
        </v-layout>
      </v-card-text>

      <!-- Card Actions -->
      <v-card-actions>
        <v-btn
          flat
          class="primary--text"
          @click.native="submit()"
        >
          Submit
        </v-btn>
      </v-card-actions>
    </v-card>

    <!-- Success / Error alerts -->
    <transition name="slide-y-reverse-transition" appear>
      <v-alert
        v-if="error"
        error
        value="true"
        class="mt-4"
      >{{ error }}</v-alert>
      <v-alert
        v-if="user && !loading && !error"
        success
        value="true"
        class="mt-4"
      >Signed in as {{ user.email }}</v-alert>
    </transition>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'home',

  // Data
  data () {
    return {
      email: undefined,
      password: undefined,
      error: undefined,
      loading: false
    }
  },

  // Computed
  computed: {
    user () {
      return this.$store.state.user
    }
  },

  // Methods
  methods: {
    /**
     * Submit the login form
     * on success, log the user in and store in state
     * on fail, show an 'Authentication Failed' message / toast
     */
    async submit () {
      this.error = undefined
      this.loading = true
      try {
        const response = await Axios.post('/api/login', {
          email: this.email,
          password: this.password
        })
        this.$store.commit('update_user', response.data.user)
      } catch (error) {
        this.handleError(error.response.data)
      } finally {
        this.loading = false
      }
    },

    /**
     * Handle the error from the api
     * Shows a toast as well as an alert below the form
     */
    handleError (data) {
      this.error = data.message
      this.$bus.$emit('toast', {
        text: data.message
          ? data.message
          : 'Oops, something went wrong...'
      })
    }
  }
}
</script>

<style scoped lang="scss">
</style>
