import Vue from 'vue'
import router from './router'
import App from './App.vue'
import Vuetify from 'vuetify'
import store from './store'
import VeeValidate from 'vee-validate'
import Clipboard from 'v-clipboard'

Vue.use(Vuetify)
Vue.use(Clipboard)

const config = {
  errorBagName: 'errors',
  fieldsBagName: 'fields',
  delay: 0,
  locale: 'en',
  strict: true
}

Vue.use(VeeValidate, config)

// Global event bus
// use 'this.$bus.$emit('event')' and 'this.$bus.$on('event')'
// To transmit events between components
const bus = new Vue({})
Object.defineProperty(Vue.prototype, '$bus', {
  get () {
    return bus
  }
})

/* eslint-disable no-new */
new Vue({
  components: { App },
  el: '#app',
  router,
  store
})
