import Vuex from "vuex";
import Vue from "vue";
import auth from "./store/auth";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        auth,
    },

    state: {
        welcomeMsg: "Hello user!",
    },

    getters: {},

    mutations: {},

    actions: {},
});
