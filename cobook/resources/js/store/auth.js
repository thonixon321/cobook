import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {},
        profile: {
            thumbnail: null,
        },
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        },
        getThumbnail(state) {
            return state.profile.thumbnail;
        },
        getUserLocation(state) {
            return state.user.location;
        },
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value;
        },
        SET_USER(state, value) {
            state.user = value;
        },
        SET_USER_LOCATION(state, value) {
            state.user.location = value;
        },
    },
    actions: {
        login({ commit }) {
            return axios
                .get("/api/user")
                .then((response) => {
                    commit("SET_USER", response.data);
                    commit("SET_AUTHENTICATED", true);
                    router.push({ name: "dashboard" });
                })
                .catch((response) => {
                    commit("SET_USER", {});
                    commit("SET_AUTHENTICATED", false);
                    alert(response);
                });
        },
        logout({ commit }) {
            commit("SET_USER", {});
            commit("SET_AUTHENTICATED", false);
        },
        me({ commit }) {
            return axios
                .get("/api/user")
                .then((response) => {
                    commit("SET_AUTHENTICATED", true);
                    commit("SET_USER", response.data);
                })
                .catch(() => {
                    commit("SET_AUTHENTICATED", false);
                    commit("SET_USER", null);
                });
        },
        userLocation({ commit }, data) {
            commit("SET_USER_LOCATION", data);
        },
    },
};
