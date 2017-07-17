<template>
  <v-app>
    <navigation-drawer></navigation-drawer>
    <toolbar></toolbar>
    <main>
      <v-container fluid>
        <transition name="slide-y-reverse-transition" mode="out-in" appear>
          <router-view></router-view>
        </transition>
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
  }
}
</script>

<style scoped lang="scss">
</style>
