<template>
  <v-navigation-drawer
    :persistent="true"
    :permanent="true"
  >
    <v-list dense subheader>
      <!--<v-subheader inset>Folders</v-subheader>-->
      <v-list-tile avatar
      v-for="item in menuItems"
      :key="item.title"
      router :to="item.path">
        <v-list-tile-avatar>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          <!--<v-list-tile-sub-title>{{ item.subtitle }}</v-list-tile-sub-title>-->
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  // Name
  name: 'navigation-drawer',

  // Components
  components: {},

  // Props
  props: {},

  // Data
  data () {
    return {
      defaultMenu: [{
        title: 'Sign In',
        icon: 'person',
        path: '/'
      }, {
        title: 'Register',
        icon: 'person_add',
        path: '/register'
      }],
      userMenu: [{
        title: 'Posts',
        icon: 'subject',
        path: '/posts'
      }, {
        title: 'Images',
        icon: 'insert_photo',
        path: '/images'
      }]
    }
  },

  // Created
  created () {},

  // Mounted
  mounted () {
    console.log('nav drawer')
  },

  // Methods
  methods: {},

  // Computed
  computed: {
    menuItems () {
      return this.user
        ? this.userMenu
        : this.defaultMenu
    },

    user () {
      return this.$store.state.user
    }
  },

  // Watch
  watch: {}

}
</script>

<style scoped lang="scss">
</style>
