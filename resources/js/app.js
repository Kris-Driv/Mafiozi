/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.config.productionTip = false

/**
 * Countdown component
 */
import VueCountdown from '@chenfengyuan/vue-countdown';
Vue.component(VueCountdown.name, VueCountdown);

/**
 * Little cool library for prompt messages
 */
import VueMessage from './libs/vue-message/src/lib/index.js';
Vue.use(VueMessage, {
    styles: {
        error:      'background: rgba(192,57,43, 0.85);',
        warning:    'background: rgba(211,84,0, 0.85);',
        info:       'background: rgba(41,128,185, 0.85);',
        success:    'background: rgba(39,174,96, 0.85);',
    }
});

/**
 * Importing Simplebar library for nice scrollbars
 */
import VueSimplebar from 'vue-simplebar';
import 'vue-simplebar/dist/vue-simplebar.min.css';
Vue.use(VueSimplebar);

/**
 * Import Carousel component
 */
import VueCarousel from '@chenfengyuan/vue-carousel';
Vue.use(VueCarousel);

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
    ...Mixin
});
window.Mixin = Mixin;

// Import views
import HomeView from './views/HomeView.vue';
import JobsView from './views/JobsView.vue';
import TopView  from './views/TopView.vue'; 

const router = new VueRouter({
    //mode: 'history',
    routes: [
        {
            name: "Home",
            path: "/",
            component: HomeView,
            meta: {
                transitionName: 'slide'
            }
        },
        {
            name: "Jobs",
            path: "/jobs",
            component: JobsView,
            meta: {
                transitionName: 'slide'
            }
        },
        {
            name: "Top",
            path: "/top",
            component: TopView,
            meta: {
                transitionName: 'slide'
            }
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
