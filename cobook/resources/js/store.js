import Vuex from "vuex";
import Vue from "vue";
import createPersistedState from "vuex-persistedstate";
import auth from "./store/auth";

Vue.use(Vuex);

export default new Vuex.Store({
    plugins: [createPersistedState()],

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
