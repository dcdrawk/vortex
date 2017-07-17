import Vue from 'vue'
import Router from 'vue-router'

// Auth
import SignIn from '../components/pages/auth/SignIn'
import Register from '../components/pages/auth/Register'

// Posts
import Posts from '../components/pages/posts/Posts'
import NewPost from '../components/pages/posts/NewPost'
import EditPost from '../components/pages/posts/EditPost'

// Images
import Images from '../components/pages/images/Images'

Vue.use(Router)

export default new Router({
  // mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Sign In',
      component: SignIn
    }, {
      path: '/register',
      name: 'Register',
      component: Register
    }, {
      path: '/posts',
      name: 'Posts',
      component: Posts
    }, {
      path: '/posts/new',
      name: 'New Post',
      component: NewPost
    }, {
      path: '/posts/:id',
      name: 'Edit Post',
      component: EditPost
    }, {
      path: '/images',
      name: 'Images',
      component: Images
    }
  ]
})
