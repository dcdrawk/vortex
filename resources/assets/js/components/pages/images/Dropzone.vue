<template>
  <div>
    <v-card class="dropzone-container">
      <v-card-title class="blue-grey white--text">
        <span class="title">Upload Files</span>
      </v-card-title>
      <div class="dropzone" fill-height @dragover.prevent @drop.prevent="dropHandler">


        <file-queue
          :files="fileQueue"
        ></file-queue>
      <v-card-text v-if="fileQueue.length === 0" class="default-message">
        <v-layout>
          <v-flex align-center class="text-xs-center">
            <div>
              <v-icon class="upload-icon">cloud_upload</v-icon>
            </div>
            Drag and Drop Files Here
          </v-flex>
        </v-layout>
      </v-card-text>
      </div>
    </v-card>
  </div>
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
      msg: 'Hello World',
      fileQueue: []
    }
  },

  // Methods
  methods: {
    dropHandler (event) {
      console.log(event)
      event.preventDefault()
      const dt = event.dataTransfer
      if (dt.items) {
        console.log('there are items!')
        for (var i = 0; i < dt.items.length; i++) {
          if (dt.items[i].kind === 'file') {
            var file = dt.items[i].getAsFile()
            this.queueFile(file)
          }
        }
      }
    },

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
        // console.log(response)
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
  // border: 3px dashed rgba(0, 0, 0, .5);
  // margin: 20px;
  // min-height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  // padding: 10px;
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