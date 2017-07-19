<template>
  <v-toolbar class="primary" dark>
    <v-toolbar-title>Vortex - {{$route.name}}</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-menu v-if="user">
      <v-btn icon dark slot="activator">
        <v-icon>more_vert</v-icon>
      </v-btn>
      <v-list>
        <v-list-tile @click.native="logout()">
          <v-list-tile-title>Log Out</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
  </v-toolbar>
</template>

<script>
import Axios from 'axios'

export default {
  // Name
  name: 'toolbar',

  // Methods
  methods: {
    async logout () {
      try {
        await Axios.post('/api/logout')
        this.$store.commit('update_user', undefined)
        this.$router.push('/')
      } catch (error) {
        console.log(error)
      }
    }
  },

  // Computed
  computed: {
    user () {
      return this.$store.state.user
    }
  }
}
</script>

<style scoped lang="scss">
</style>
