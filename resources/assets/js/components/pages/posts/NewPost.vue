<template>
  <div>
    <v-card>

      <!-- Card Title -->
      <v-card-title class="blue-grey white--text">
        <div class="title">New Post</div>
      </v-card-title>

      <!-- Card Text -->
      <v-card-text>
        <v-layout column>
          <!-- Title -->
          <v-flex>
            <v-text-field
              class="mb-0"
              name="title"
              label="Title"
              v-model="title"
              :error-messages="getErrors('title')"
              v-validate="'required'"
              required
            ></v-text-field>
          </v-flex>

          <!-- Body -->
          <v-flex>
            <v-text-field
              class="mb-0"
              label="Body"
              name="body"
              v-model="body"
              :error-messages="getErrors('body')"
              v-validate="'required'"
              multi-line
              required
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-card-text>

      <!-- Card Actions -->
      <v-card-actions>
        <v-btn class="primary"
        @click.native="submit()">
          Create
        </v-btn>
        <v-btn
          flat
          router to="/posts"
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'new-post',

  // Data
  data () {
    return {
      posts: undefined,
      title: undefined,
      body: undefined
    }
  },

  // Methods
  methods: {
    getErrors (fieldName) {
      return this.errors.first(fieldName)
        ? [ this.errors.first(fieldName) ]
        : undefined
    },

    async submit () {
      try {
        const valid = await this.$validator.validateAll()
        if (!valid) throw 'Invalid Fields.'
        await Axios.post('/api/posts', {
          title: this.title,
          body: this.body
        })
        this.$bus.$emit('toast', {
          text: 'New Post Created',
          button: true
        })
        this.$router.push('/posts')
      } catch (error) {
        this.handleError(error)
      }
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
