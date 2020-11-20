/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.config.productionTip = false

/**
 * Importing Simplebar library for nice scrollbars
 */
import VueSimplebar from 'vue-simplebar';
import 'vue-simplebar/dist/vue-simplebar.min.css';
Vue.use(VueSimplebar);

/**
 * Importing cookies library to handle refresh, animations and authentication
 */
import VueCookies from 'vue-cookies';
Vue.use(VueCookies)

/**
 * Router
 */
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/**
 * Vuex state managment library
 */
import Vuex from 'vuex';
Vue.use(Vuex);

/**
 * Mixing functions, making it globally accessable
 */
import Mixin from './mixin.js';

/**
 * Declare helper functions
 */
Vue.mixin({
    methods: {
        ...Mixin
    }
});

// Import views
import HomeView from './views/HomeView.vue';
import JobsView from './views/JobsView.vue';

const router = new VueRouter({
    //mode: 'history',
    routes: [
        {
            name: "Home",
            path: "/",
            component: HomeView
        },
        {
            name: "Jobs",
            path: "/jobs",
            component: JobsView
        }
    ],
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
});

/**
 * Import the Vuex store
 */
import store from './store.js';

/**
 * Render the application
 */
import Entry from './Entry.vue';

new Vue({
    render: h => h(Entry),
    store,
    router
}).$mount('#app')
