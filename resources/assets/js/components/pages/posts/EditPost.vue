<template>
  <div>
    <v-card>

      <!-- Card Title -->
      <v-card-title class="blue-grey white--text">
        <div class="title">Edit Post</div>
      </v-card-title>

      <!-- Card Text -->
      <v-card-text v-if="post">
        <v-layout column>
          <!-- Post Title -->
          <v-flex>
            <v-text-field
              class="mb-0"
              name="title"
              label="Title"
              v-model="post.title"
              :error-messages="getErrors('title')"
              v-validate="'required'"
              required
            ></v-text-field>
          </v-flex>

          <!-- Post Body -->
          <v-flex>
            <v-text-field
              class="mb-0"
              label="Body"
              name="body"
              v-model="post.body"
              :error-messages="getErrors('body')"
              v-validate="'required'"
              multi-line
              required
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-card-text>

      <!-- Loading Spinner -->
      <v-card-text v-if="loading">
        <v-layout column>
          <v-flex
          class="text-xs-center">
            <v-progress-circular indeterminate class="primary--text"></v-progress-circular>
          </v-flex>
        </v-layout>
      </v-card-text>

      <!-- Card Actions -->
      <v-card-actions v-if="post">
        <v-btn class="primary"
        @click.native="savePost()">
          Save
        </v-btn>
        <v-btn class="red" dark
        @click.native.stop="showDialog = true">
          Delete
        </v-btn>
        <v-btn
          flat
          router to="/posts"
        >
          Back
        </v-btn>
      </v-card-actions>
    </v-card>

    <!-- Delete Dialog -->
    <v-dialog v-model="showDialog">
      <v-card>
        <v-card-title>
          <div class="headline">Delete this post?</div>
        </v-card-title>
        <v-card-text>This action cannot be undone.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="showDialog = false">Cancel</v-btn>
          <v-btn class="red--text" flat @click.native="deletePost(); showDialog = false">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'edit-post',

  // Data
  data () {
    return {
      post: undefined,
      showDialog: false,
      loading: false
    }
  },

  // Mounted
  mounted () {
    this.getPost()
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
     * Get the post via the {id} in the route params
     */
    async getPost () {
      try {
        this.loading = true
        const id = this.$route.params.id
        const response = await Axios.get(`/api/posts/${id}`)
        this.post = response.data.post
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Delete a post by its {id}
     */
    async deletePost () {
      try {
        this.loading = true
        this.showDialog = false
        await Axios.post(`/api/posts/${this.post.id}/delete`)
        this.$bus.$emit('toast', {
          text: 'Post deleted.'
        })
        this.$router.push('/posts')
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Save a post by its {id}
     */
    async savePost () {
      try {
        this.loading = true
        const valid = await this.$validator.validateAll()
        if (!valid) throw 'Invalid Fields.'
        await Axios.post(`/api/posts/${this.post.id}`, {
          title: this.post.title,
          body: this.post.body
        })
        this.$bus.$emit('toast', {
          text: 'Post updated successfully.'
        })
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Handle Any potential errors
     */
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
  }
}
</script>

<style scoped lang="scss">
</style>
