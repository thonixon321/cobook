require("./bootstrap");

import Vue from "vue";
window.Vue = require("vue");

import router from "./routes.js";
window.router = router;

import store from "./store.js";

import VueGeolocation from "vue-browser-geolocation";

import * as VueGoogleMaps from "vue2-google-maps";

window.Fire = new Vue();

Vue.use(VueGeolocation);

Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyC1-zoxsrNbemxUUtgNn--oDDDLnstXPmY",
    },
});

store.dispatch("auth/me").then(() => {
    new Vue({
        el: "#app",
        router,
        store,
    }).$mount("#app");
});
