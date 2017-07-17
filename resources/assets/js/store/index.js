import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: undefined,
    userId: localStorage.getItem('userId')
      ? localStorage.getItem('userId')
      : undefined
  },
  mutations: {
    update_user (state, user) {
      state.user = user
      if (user) {
        state.userId = user.id
        localStorage.setItem('userId', user.id)
      } else {
        localStorage.removeItem('userId')
      }
    }
  }
})
