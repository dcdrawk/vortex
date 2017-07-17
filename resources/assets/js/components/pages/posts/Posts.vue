<template>
  <div>
    <v-card>

      <!-- Card Title -->
      <v-card-title class="blue-grey white--text">
        <div class="title">Posts</div>
        <v-spacer></v-spacer>
        <v-btn
          class="ma-0 ml-2"
          router to="/posts/new"
        >
          New Post
        </v-btn>
      </v-card-title>

      <!-- Card Text -->
      <v-list two-line>
        <v-list-tile
          v-for="post in posts"
          :key="post.id"
          router :to="`/posts/${post.id}`"
        >
          <v-list-tile-content>
            <v-list-tile-title>{{ post.title }}</v-list-tile-title>
            <v-list-tile-sub-title>{{ post.body }}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
      <v-card-text v-if="loading">
        <v-layout column>
          <v-flex
          class="text-xs-center">
            <v-progress-circular indeterminate class="primary--text"></v-progress-circular>
          </v-flex>
        </v-layout>
      </v-card-text>
      <v-card-text  v-if="posts.length === 0 && !loading">
        <v-layout>
          <v-flex>
            No Posts Found.
          </v-flex>
        </v-layout>

      </v-card-text>

      <!-- Card Actions -->
      <!--<v-card-actions>

      </v-card-actions>-->
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
      posts: [],
      loading: false
    }
  },

  // Created
  created () {},

  // Mounted
  mounted () {
    console.log('Home!')
    this.getPosts()
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

    async getPosts () {
      try {
        this.loading = true
        const response = await Axios.get('/api/posts')
        this.posts = response.data.posts
        // this.router.push('/')
      } catch (error) {
        console.warn(error)
        // this.handleError(error)
      } finally {
        this.loading = false
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
