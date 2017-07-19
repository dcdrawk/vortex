<template>
  <div>
    <!-- <v-alert warning value="true" v-if="!user">
      You must sign in to view this page.
    </v-alert> -->

    <div>
      <v-card tile>
        <!-- Progress Bar -->
        <v-progress-linear
          v-if="loading"
          indeterminate
          class="ma-0"
        ></v-progress-linear>

        <v-layout row wrap v-if="image">
          <v-flex xs12 md6>

            <img class="image" :src="image.src">
          </v-flex>
          <v-flex xs12 md6>
            <v-card-text>
              <!-- File Name -->
              <v-text-field
                label="File Name"
                v-model="image.name"
                :readonly="readonly"
                :append-icon="readonly ? 'edit' : 'save'"
                :append-icon-cb="() => { toggleReadonly() }"
              ></v-text-field>

              <!-- URL -->
              <v-text-field
                label="URL"
                :value="getUrl(image)"
                readonly
              ></v-text-field>

              <p>Tags</p>
              <v-chip
              v-for="(chip, index) in image.image_tags"
              :key="chip"
              close
              @input="removeTag(index)"
              >{{ chip }}</v-chip>

              <v-text-field
                placeholder="Add Tag"
                v-model="tag"
                @keyup.enter.native="addTag()"
              ></v-text-field>
              <div v-if="!editTags">
                <v-btn>Edit Tags</v-btn>
              </div>
            </v-card-text>
          </v-flex>
        </v-layout>
      </v-card>
    </div>
  </div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'edit-image',

  // Components
  components: {
  },

  // Data
  data () {
    return {
      image: undefined,
      loading: false,
      readonly: true,
      tag: undefined,
      editTags: false
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
    this.getImage()
  },

  // Methods
  methods: {
    addTag () {
      this.image.image_tags.push(this.tag)
      this.tag = undefined
    },

    removeTag (index) {
      this.image.image_tags.splice(index, 1)
    },

    /**
     * Get a list of images from the API
     * @param {string} [url] - next page URL from the api
     */
    async getImage () {
      try {
        this.loading = true
        const id = this.$route.params.id
        const response = await Axios.get(`/api/images/${id}`)
        this.image = response.data.image
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Get a list of images from the API
     * @param {string} [url] - next page URL from the api
     */
    async saveImage () {
      try {
        this.loading = true
        const id = this.$route.params.id
        const response = await Axios.post(`/api/images/${id}`, {
          name: this.image.name
        })
        this.image = response.data.image
      } catch (error) {
        console.warn(error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Returns the full URL of the image
     * @param {Object} image - the image object from the API
     * @returns {String} - URL to the image
     */
    getUrl (image) {
      return `${window.location.origin}${image.src}`
    },

    toggleReadonly () {
      this.readonly = !this.readonly
      if (this.readonly) {
        this.saveImage()
      }
    }
  }
}
</script>

<style scoped lang="scss">
.image {
  display: block;
  width: 100%;
}
</style>
