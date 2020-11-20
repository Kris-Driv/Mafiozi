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
 * Router for Mafiozi SPA (Single Page Application) needs
 */
import VueRouter from 'vue-router';
Vue.use(VueRouter)

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

/**
 * Import the Vuex store
 */
import store from './store.js';

/**
 * Dynamically import components
 */
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



/**
 * Render the application
 */
import Entry from './Entry.vue';
import { isString } from 'lodash';

new Vue({
    render: h => h(Entry),
    store
}).$mount('#app')
