require('./bootstrap');

import Vue from 'vue'
window.Vue = require('vue');

import router from './routes.js';
window.router = router;

import store from './store.js';

window.Fire = new Vue();

const app = new Vue({
    el: '#app',
    router,
    store
}).$mount('#app');
