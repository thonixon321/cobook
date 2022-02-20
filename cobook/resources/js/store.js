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
        workshops: [],
    },

    getters: {
        getWorkshops(state) {
            return state.workshops;
        },
    },

    mutations: {
        SET_WORKSHOPS(state, value) {
            state.workshops = value;
        },
    },

    actions: {
        workshops({ commit }) {
            return axios
                .get("/api/workshops")
                .then((response) => {
                    console.log({
                        data: response.data.data,
                    });
                    commit("SET_WORKSHOPS", response.data.data);
                })
                .catch((error) => {
                    alert(error);
                });
        },
    },
});
