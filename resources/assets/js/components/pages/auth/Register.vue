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
          <!--{{ errors }}-->
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
              v-validate="'required|confirmed:confirm_password'"
              required
            ></v-text-field>
          </v-flex>

          <!-- Re-Password -->
          <v-flex>
            <v-text-field
              type="password"
              class="mb-0"
              name="confirm_password"
              label="Confirm Password"
              v-model="confirmPassword"
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
        >
          Submit
        </v-btn>

      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'home',

  // Components
  components: {},

  // Props
  props: {},

  // Data
  data () {
    return {
      email: undefined,
      name: undefined,
      password: undefined,
      confirmPassword: undefined,
      error: undefined
    }
  },

  // Created
  created () {},

  // Mounted
  mounted () {
    console.log('Home!')
  },

  // Methods
  methods: {
    getErrors (fieldName) {
      return this.errors.first(fieldName)
        ? [ this.errors.first(fieldName) ]
        : undefined
      // return [() => {
      //   return this.errors.first(fieldName)
      //     ? this.errors.first(fieldName)
      //     : true
      // }]
    },

    async submit () {
      try {
        const valid = await this.$validator.validateAll()
        if (!valid) throw 'Invalid Fields.'
        await Axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword
        })
        this.router.push('/')
      } catch (error) {
        console.warn(error)
        this.handleError(error)
      }
      // try {
      //   const test = await this.$validator.validateAll()
      //   console.log(test)
      //   const response = await Axios.post('/register', {
      //     namne: this.name,
      //     email: this.email,
      //     password: this.password
      //   })
      //   console.log(response)
      //   // this.error = undefined
      //   // this.$store.commit('update_user', response.data.user)
      // } catch (error) {
      //   this.error = 'Something went wrong!'
      //   this.handleError(error.response.data)
      // }
    },

    handleError (data) {
      if (typeof data !== 'object') {
        this.$bus.$emit('toast', {
          text: 'An Error Occurred. Please try again.'
        })
        return
      }
      for (let i in data) {
        if (Array.isArray(data[i])) {
          this.$bus.$emit('toast', {
            text: data[i][0], button: true
          })
        } else {
          this.$bus.$emit('toast', {
            text: data[i], button: true
          })
        }
      }
    }
  },

  // Computed
  computed: {},

  // Watch
  watch: {}

}
</script>

<style scoped lang="scss">
</style>
