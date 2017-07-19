<template>
  <v-card tile>
    <transition name="fade-transition">

    <v-layout row wrap v-if="image">
      <!-- Image -->
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

          <!-- Tags -->
          <p class="caption">
            Tags
          </p>

          <!-- No Chips Message -->
          <p class="body-1" v-if="image.image_tags.length === 0">
            No Tags Found
          </p>

          <v-chip
            v-for="(chip, index) in image.image_tags"
            :key="chip"
            :close="editingTags"
            @input="removeTag(index)"
          >{{ chip }}</v-chip>

          <v-text-field
            v-if="editingTags"
            placeholder="+ Add Tag"
            v-model="tag"
            @keyup.enter.native="addTag()"
          ></v-text-field>

          <!-- Edit Tags -->
          <div v-if="!editingTags">
            <v-btn flat @click.native="editTags(true)">Edit Tags</v-btn>
          </div>
          <div v-else>
            <v-btn flat @click.native="editTags(false)">Cancel</v-btn>
            <v-btn class="primary" @click="saveTags()">Save</v-btn>
          </div>
        </v-card-text>
      </v-flex>
    </v-layout>
    </transition>

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
      editingTags: false,
      tagsCopy: undefined
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

    editTags (value) {
      this.editingTags = value
      this.tag = undefined
      if (value) {
        this.tagsCopy = Object.assign([], this.image.image_tags)
      } else {
        this.image.image_tags = this.tagsCopy
      }
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

    async saveTags () {
      try {
        this.loading = true
        this.editingTags = false
        const id = this.$route.params.id
        const tags = this.image.image_tags.join(',')
        const response = await Axios.post(`/api/images/${id}`, {
          tags: tags
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
.chip {
  transition: none;
}
</style>
