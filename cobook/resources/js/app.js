require("./bootstrap");

import Vue from "vue";
window.Vue = require("vue");

import router from "./routes.js";
window.router = router;

import store from "./store.js";

import * as VueGoogleMaps from "vue2-google-maps";

window.Fire = new Vue();

Vue.use(VueGoogleMaps, {
    load: {
        key: "",
    },
});

const app = new Vue({
    el: "#app",
    router,
    store,
}).$mount("#app");
