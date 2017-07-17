<template>
  <!-- Toast / Snackbar -->
  <v-snackbar
    :timeout="toast.timeout || duration"
    :multi-line="toast.multiLine"
    v-model="show"
  >
    <!-- Text -->
    {{ toast.text }}

    <!-- 'Close' Button -->
    <v-btn
      v-if="toast.button"
      flat
      class="white--text"
      @click.native="close()"
    >
      Close
    </v-btn>
  </v-snackbar>
</template>

<script>
/**
 * <toast></toast>
 * Toast / Snackbar component
 * @desc uses this.$bus to listen for 'toast' events containing a toast object
 * If there are multiple toasts, it will queue them
*/
export default {
  // Name
  name: 'toast',

  // Data
  data () {
    return {
      delay: 500, // deplay between toasts
      duration: 3000, // duration toast is on screen
      processing: false, // is the queue being processed?
      queue: [], // array of toasts
      show: false, // show the toast
      timeout: undefined, // set the queue timeout to a variable
      toast: {} // Toast object
    }
  },

  // Mounted
  mounted () {
    this.$bus.$on('toast', (toast) => { // Listen for a 'toast' event
      console.log(toast)
      this.$nextTick(() => {
        this.queueToast(toast) // Queue up the toast
      })
    })
  },

  // Methods
  methods: {
    /**
     * If the user clicks on 'close', hide the toast
     * if there are more toasts in the queue, show the next
     */
    close () {
      clearTimeout(this.timeout)
      this.show = false
      this.queue.shift()
      setTimeout(() => {
        if (this.queue.length > 0) {
          this.processQueue()
        } else {
          this.processing = false
        }
      }, this.delay)
    },

    /**
     * Add the toast the queue
     * Start processing the queue if it's not already
     * @param toast {Object}
     */
    queueToast (toast) {
      this.queue.push(toast)
      if (!this.processing) {
        this.processQueue()
      }
    },

    /**
     * Process the queue of toasts
    */
    processQueue () {
      this.processing = true
      this.toast = this.queue[0] // grab the first toast
      this.$nextTick(() => {
        this.show = true
      })
      this.timeout = setTimeout(() => {
        this.show = false
        this.queue.shift()
        if (this.queue.length > 0) {
          this.processQueue()
        } else {
          this.processing = false
        }
      }, (this.toast.timeout || this.duration) + this.delay)
    }
  }
}
</script>

<style scoped lang="scss">
</style>
