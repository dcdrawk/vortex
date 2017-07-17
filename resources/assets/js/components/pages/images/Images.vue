<template>
  <div>
    <transition name="slide-y-transition">
      <v-alert warning value="true" v-if="!user">
        You must sign in to view this page.
      </v-alert>

      <div v-else>
        <!-- Dropzone -->
        <dropzone
          v-if="user"
          @upload-complete="addImage($event)"
        ></dropzone>

        <image-gallery
          v-if="user"
          :images="images"
          :loading="loading"
          :nextUrl="nextUrl"
          @load-more="getImages(nextUrl)"
        ></image-gallery>
      </div>
    </transition>
  </div>
</template>

<script>
import Axios from 'axios'
import Dropzone from './Dropzone'
import ImageGallery from './ImageGallery'
// import Clipboard from 'clipboard'

export default {
  // Name
  name: 'images',

  // Components
  components: {
    Dropzone,
    ImageGallery
  },

  // Data
  data () {
    return {
      images: [],
      loading: false,
      nextUrl: undefined
    }
  },

  // Computed
  computed: {
    user () {
      return this.$store.state.user
    }
  },

  // Mounted
  mounted () {
    this.images = []
    this.getImages()
  },

  // Methods
  methods: {
    getErrors (fieldName) {
      return this.errors.first(fieldName)
        ? [ this.errors.first(fieldName) ]
        : undefined
    },

    async getImages (url) {
      try {
        this.loading = true
        const response = await Axios.get(url || '/api/images')
        if (this.images.length === 0) {
          this.images = response.data.images.data
        } else {
          this.images = this.images.concat(response.data.images.data)
        }
        this.nextUrl = response.data.images.next_page_url
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    addImage (image) {
      this.images.pop()
      this.images.unshift(image)
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
  }
}
</script>

<style scoped lang="scss">

</style>
