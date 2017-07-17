<template>
  <!-- App Container -->
  <!--<div id="app">-->
    <!-- Router View -->
  <shell>
    <!--<router-view></router-view>-->
    <toast></toast>
  </shell>
  <!--</div>-->
</template>

<script>
import Shell from './components/layout/Shell'
import Toast from './components/Toast'

export default {
  name: 'app',
  components: {
    Shell,
    Toast
  }
}
</script>

<style>
</style>
