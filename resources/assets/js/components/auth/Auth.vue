<template>
  <div class="auth"></div>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'auth',

  // Mounted
  mounted () {
    if (this.userId) {
      this.checkUser()
    }
  },

  // Methods
  methods: {
    async checkUser () {
      try {
        const response = await Axios.get('/api/user')
        const user = response.data.user
        this.$store.commit('update_user', user)
      } catch (error) {
        console.warn(error)
        this.$router.push('/')
      }
    }
  },

  // Computed
  computed: {
    userId () {
      return this.$store.state.userId
    }
  }
}
</script>

<style scoped lang="scss">
</style>
