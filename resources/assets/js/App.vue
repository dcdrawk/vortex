<template>
  <!-- App Container -->
  <shell>
    <toast></toast>
  </shell>
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
