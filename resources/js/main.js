import Vue from 'vue'
import App from './App.vue'

Vue.config.productionTip = false

// Simplebar
import VueSimplebar from 'vue-simplebar';
import 'vue-simplebar/dist/vue-simplebar.min.css';
Vue.use(VueSimplebar);

// Cookies
import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

// Router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Vuex
import Vuex from 'vuex';
Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    access_token: null
  },
  mutations: {
    setToken(state, token) {
      state.access_token = token;
    }
  },
  actions: {
  },
  getters: {

  }
});

new Vue({
  render: h => h(App),
  store
}).$mount('#app')
