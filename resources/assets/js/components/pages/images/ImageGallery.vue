<template>
  <!-- Card -->
  <v-card class="mt-2">
    <!-- Card Title -->
    <v-card-title class="blue-grey white--text" justify-start flexbox>
      <div class="title">Image Gallery</div>
      <v-spacer></v-spacer>

      <v-select
        class="search-bar ma-0"
        dark
        v-model="search"
        label="Filter by Tag"
        single-line
        :items="tags"
        item-value="name"
        item-text="name"
        :append-icon="!search ? 'search' : ''"
        :append-icon-cb="() => { if (search) test() }"
        @input="$emit('search', search)"
      >

      </v-select>
      <v-icon dark class="close-icon" v-if="search" @click="search = undefined">
        close
      </v-icon>
    </v-card-title>


    <!-- No Images Found Text -->
    <v-card-text v-if="images.length === 0 && !loading">
      <v-layout>
        <v-flex>
          No images Found.
        </v-flex>
      </v-layout>
    </v-card-text>

    <!-- Image List Container -->
    <v-container fluid grid-list-md v-if="images.length > 0">
      <transition-group name="slide-y-reverse-transition" tag="v-layout" class="row wrap" appear>
        <!-- Image List -->
        <v-flex xs12 sm6 md4 lg3 xl2 v-for="(image, index) in images" :key="image.name">
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
                    <!-- Copy Icon -->
                    <v-btn
                      icon dark
                      class="ma-0 mr-1 copy-icon"
                      v-clipboard="getUrl(image)"
                      @click.native.stop="showCopyToast"
                    >
                      <v-icon>content_copy</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <!-- Image Name -->
                    <span v-text="image.name"></span>

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
    <!-- Progress Bar -->
    <v-progress-linear
      v-if="loading"
      indeterminate
      class="ma-0"
    ></v-progress-linear>
  </v-card>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'image-gallery',

  // Props
  props: {
    images: Array,
    loading: Boolean,
    nextUrl: String
  },

  // Data
  data () {
    return {
      search: undefined,
      tags: undefined
    }
  },

  mounted () {
    this.getTags()
  },

  // Methods
  methods: {
    /**
     * Returns the full URL of the image
     * @param {Object} image - the image object from the API
     * @returns {String} - URL to the image
     */
    getUrl (image) {
      return `${window.location.origin}${image.src}`
    },

    /**
     * Get an array of image tags from the API
     */
    async getTags () {
      try {
        const response = await Axios.get('api/images/tags')
        this.tags = response.data.tags
      } catch (error) {
        console.warn(error)
      }
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
    },

    test () {
      console.log('test')
    }
  }
}
</script>

<style scoped lang="scss">
.close-icon {
  position: absolute;
  right: 16px;
  cursor: pointer;
  // top: 10px;
}
.image-name {
  position: absolute;
  bottom: 0;
  background-color: rgba(0, 0, 0, .5);
}
.slide-y-reverse-transition-move {
  transition: transform 250ms ease-out;
}
.search-bar {
  position: relative;
  color: #fff;
  max-width: 200px;
  // top: 8px;
  // height: 30px;
  ::-webkit-input-placeholder {
    color: #fff;
  }
  .input-group__details:after {
    background-color: #fff;
  }
}
.copy-icon {
  min-width: 36px;
}
</style>
<style lang="scss">
.search-bar {
  .input-group__details:after {
    background-color: #fff;
  }
  &.input-group.input-group--focused .input-group__input .icon {
    color: #fff;
  }
  .input-group__details {
    padding-top: 1px;
    min-height: 0px;
    // display: none;
  }
  label {
    color: #fff !important;
  }
  &.input-group--select.input-group--focused .input-group__append-icon {
    transform: rotate(0deg) !important;
  }
}
</style>