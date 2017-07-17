<template>
  <v-card class="mt-2">
    <!-- Card Title -->
    <v-card-title class="blue-grey white--text">
      <div class="title">Image Gallery</div>
      <v-spacer></v-spacer>
    </v-card-title>

    <!-- No Images Found Text -->
    <v-card-text v-if="images.length === 0 && !loading">
      <v-layout>
        <v-flex>
          No images Found.
        </v-flex>
      </v-layout>
    </v-card-text>

    <!-- Loading Spinner -->
    <v-card-text v-if="images.length === 0 && loading">
      <v-layout>
        <v-flex class="text-xs-center">
          <v-progress-circular indeterminate class="primary--text"></v-progress-circular>
        </v-flex>
      </v-layout>
    </v-card-text>

    <!-- Image List Container -->
    <v-container fluid grid-list-md>
      <transition-group name="slide-y-reverse-transition" tag="v-layout" class="row wrap">
        <!-- Image List -->
        <v-flex xs3 lg2 xl1 v-for="(image, index) in images" :key="image.name">
          <!-- Image Card -->
          <v-card flat tile @click="$router.push(`images/${image.id}`)">
            <v-card-media
              class="image"
              :src="image.thumb_src"
              alt="lorem"
              height="200px"
            >
              <v-container fluid class="image-name">
                <v-layout fill-height>
                  <v-flex xs12 align-center flexbox class="white--text">
                    <!-- Image Name -->
                    <span v-text="image.name"></span>
                    <v-spacer></v-spacer>

                    <!-- Copy Icon -->
                    <v-btn
                      icon dark
                      class="ma-0"
                      v-clipboard="getUrl(image)"
                      @click.native.stop="showCopyToast"
                    >
                      <v-icon>content_copy</v-icon>
                    </v-btn>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-media>
          </v-card>
        </v-flex>
      </transition-group>

      <!-- Load More Button -->
      <v-layout>
        <v-flex flexbox justify-center xs12 v-if="nextUrl">
          <v-btn
            primary
            :loading="loading"
            @click.native="$emit('load-more')"
          >
            Load More
          </v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
export default {
  // Name
  name: 'image-gallery',

  // Props
  props: {
    images: Array,
    loading: Boolean,
    nextUrl: String
  },

  // Methods
  methods: {
    /**
     * Returns the full URL of the image
     * @param {Object} image - the image object from the API
    */
    getUrl (image) {
      return `${window.location.origin}${image.src}`
    },

    /**
     * Show a toast when the URL is copied
    */
    showCopyToast () {
      this.$bus.$emit('toast', {
        text: 'URL Copied to Clipboard',
        button: true,
        timeout: 1000
      })
    }
  }
}
</script>

<style scoped lang="scss">
.image-name {
  position: absolute;
  bottom: 0;
  background-color: rgba(0, 0, 0, .5);
}
.slide-y-reverse-transition-move {
  transition: transform 250ms ease-out;
}
</style>
