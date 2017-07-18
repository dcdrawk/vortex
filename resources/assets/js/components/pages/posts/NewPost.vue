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

    /**
     * Submit the new post to the API
     * On success - Shows a toast and redirects to the /posts page
     * On error - Show the errors from the API as toasts
     */
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

    /**
     * Handle any errors from the api
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
