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
        dashboardActive: true,
        workshopsActive: false,
        hostActive: false,
        mapCenter: {
            lat: "",
            lng: "",
        },
    },

    getters: {
        getWorkshops(state) {
            return state.workshops;
        },

        getMapCenter(state) {
            return state.mapCenter;
        },
    },

    mutations: {
        SET_WORKSHOPS(state, value) {
            state.workshops = value;
        },

        SET_ACTIVE_LINK(state, value) {
            state.dashboardActive = false;
            state.workshopsActive = false;
            state.hostActive = false;

            if (value == "dashboard") {
                state.dashboardActive = true;
            } else if (value == "workshops") {
                state.workshopsActive = true;
            } else {
                state.hostActive = true;
            }
        },

        SET_MAP_CENTER(state, data) {
            state.mapCenter = data;
        },
    },

    actions: {
        workshops({ commit }, query) {
            return axios
                .get("/api/workshops" + query)
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

        activateLink({ commit }, type) {
            commit("SET_ACTIVE_LINK", type);
        },

        centerMap({ commit }, data) {
            console.log({ coordinates: data });
            commit("SET_MAP_CENTER", data);
        },
    },
});
