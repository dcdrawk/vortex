<template>
  <v-app>
    <navigation-drawer></navigation-drawer>
    <toolbar></toolbar>
    <main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
      <slot></slot>
    </main>
    <auth></auth>
  </v-app>
</template>

<script>
import Auth from '../auth/Auth'
import NavigationDrawer from './NavigationDrawer'
import Toolbar from './Toolbar'

export default {
  // Name
  name: 'shell',

  // Components
  components: {
    Auth,
    NavigationDrawer,
    Toolbar
  },

  // Props
  props: {},

  // Data
  data () {
    return {
      msg: 'Hello World'
    }
  },

  // Created
  created () {},

  // Mounted
  mounted () {},

  // Methods
  methods: {},

  // Computed
  computed: {},

  // Watch
  watch: {}

}
</script>

<style scoped lang="scss">
</style>
