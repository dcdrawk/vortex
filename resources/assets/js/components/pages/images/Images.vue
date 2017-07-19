<template>
  <div>
    <transition name="slide-y-reverse-transition" mode="out-in">
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
    /**
     * Get a list of images from the API
     * @param {string} [url] - next page URL from the api
     */
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

    /**
     * Add an image to the beginning of the images array,
     * remove the one at the end
     * @param {Object} image
     */
    addImage (image) {
      this.images.pop()
      this.images.unshift(image)
    }
  }
}
</script>

<style scoped lang="scss">
</style>
