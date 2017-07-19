<template>
  <!-- Card -->
  <v-card class="dropzone-container">
    <!-- Card Title -->
    <v-card-title class="blue-grey white--text">
      <span class="title">Upload Files {{ dragover }}</span>
    </v-card-title>

    <!-- Dropzone -->
    <div class="dropzone"
      :class="{'hover': dragover}"
      fill-height
      @drop.prevent="dropHandler"
      @dragover.prevent="dragover = true"
      @dragend.prevent="dragover = false"
      @dragleave.prevent="dragover = false"
    >
      <!-- File Queue -->
      <file-queue
        :files="fileQueue"
      ></file-queue>
      <!-- Card Text -->
      <v-card-text v-if="fileQueue.length === 0" class="default-message">
        <v-layout>
          <v-flex align-center column class="text-xs-center">
            <div>
              <v-icon class="upload-icon">cloud_upload</v-icon>
            </div>
            Drag and Drop Files Here
          </v-flex>
        </v-layout>
      </v-card-text>
    </div>
  </v-card>
</template>

<script>
import Axios from 'axios'
import throttle from 'lodash.throttle'
import FileQueue from './FileQueue'

export default {
  // Name
  name: 'dropzone',

  // Components
  components: {
    FileQueue
  },

  // Data
  data () {
    return {
      fileQueue: [],
      dragover: false
    }
  },

  // Methods
  methods: {
    /**
     * Handles drop events for the dropzone
     * @param {Object} - event
     */
    dropHandler (event) {
      event.preventDefault()
      const dt = event.dataTransfer
      if (dt.items) {
        for (var i = 0; i < dt.items.length; i++) {
          if (dt.items[i].kind === 'file') {
            var file = dt.items[i].getAsFile()
            this.queueFile(file)
          }
        }
      }
    },

    /**
     * Read a file and queue it for upload
     * @param {Object} - file
     */
    queueFile (file) {
      const reader = new FileReader()
      reader.addEventListener('load', () => {
        const newFile = {
          src: reader.result,
          progress: 0,
          complete: false
        }
        this.fileQueue.push(newFile)
        const index = this.fileQueue.length - 1
        this.uploadFile(file, index)
      })

      reader.readAsDataURL(file)
    },

    /**
     * Upload a file to the api
     * Keeps track of the upload progress and updates the ui
     * @param {Object} - file
     * @param {number} - index
     * @emits {Object} - @upload-complete
     */
    async uploadFile (file, index) {
      const data = new FormData()
      data.append('file', file)
      try {
        const response = await Axios.post('/api/images', data, {
          'Content-Type': 'multipart/form-data',
          onUploadProgress: throttle((event) => {
            const uploadPercent = (event.loaded / event.total) * 100
            this.fileQueue[index].progress = +uploadPercent.toFixed(0)
          }, 250)
        })
        this.fileQueue[index].complete = true
        const image = response.data
        this.$emit('upload-complete', image)
      } catch (error) {
        console.warn(error)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.dropzone-container {
  background-color: #eee;
}
.dropzone {
  min-height: 220px;
  background-color: #eee;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all .25s ease-out;
  &.hover {
    background-color: #E0E0E0;
  }
}

.default-message {
  height: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.upload-icon {
  font-size: 42px;
}

</style>

<style lang="scss">
.file-queue__progress {
  .progress-circular__overlay {
    transition: all .25s ease-in-out;
  }
}
</style>