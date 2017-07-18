<template>
  <div>
    <v-card>
      <!-- Card Title -->
      <v-card-title class="blue-grey white--text">
        <div class="title">Register - New User</div>
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
              :error-messages="getErrors('email')"
              v-validate="'required'"
              required
            ></v-text-field>
          </v-flex>

          <!-- Name -->
          <v-flex>
            <v-text-field
              class="mb-0"
              name="name"
              label="Name"
              v-model="name"
              :error-messages="getErrors('name')"
              v-validate="'required'"
              required
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
              :error-messages="getErrors('password')"
              v-validate="'required|confirmed:Confirm Password|min:6'"
              required
            ></v-text-field>
          </v-flex>

          <!-- Confirm Password -->
          <v-flex>
            <v-text-field
              type="password"
              class="mb-0"
              name="Confirm Password"
              label="Confirm Password"
              v-model="confirmPassword"
              :error-messages="getErrors('Confirm Password')"
              v-validate="'required'"
              required
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-card-text>

      <!-- Card Actions -->
      <v-card-actions>
        <v-btn
          flat
          class="primary--text"
          @click.native="submit()"
        >Submit</v-btn>
      </v-card-actions>

      <!-- Progress Bar -->
      <v-progress-linear
        v-if="loading"
        indeterminate
      ></v-progress-linear>
    </v-card>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'register',

  // Data
  data () {
    return {
      email: undefined,
      name: undefined,
      password: undefined,
      confirmPassword: undefined,
      error: undefined,
      loading: false
    }
  },

  // Methods
  methods: {
    /**
     * Get the validation errors for that field
     * @param {String} fieldName - the name of the input field
     */
    getErrors (fieldName) {
      return this.errors.first(fieldName)
        ? [ this.errors.first(fieldName) ]
        : undefined
    },

    /**
     * Submit the registration form
     * on success - show a toast and redirect to the Sign In page
     * on fail - show an error toast
     */
    async submit () {
      this.loading = true
      try {
        const valid = await this.$validator.validateAll()
        if (!valid) return
        await Axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword
        })
        this.$bus.$emit('toast', {
          text: 'Registration Successful.'
        })
        this.$router.push('/')
      } catch (error) {
        this.handleError(error.response.data)
      } finally {
        this.loading = false
      }
    },

    /**
     * Handle any errors from the API
     * @param {Object} - data
     */
    handleError (data) {
      for (let i in data) {
        this.$bus.$emit('toast', {
          text: Array.isArray(data[i]) ? data[i][0] : data[i],
          button: true
        })
      }
    }
  }
}
</script>

<style scoped lang="scss">
</style>
