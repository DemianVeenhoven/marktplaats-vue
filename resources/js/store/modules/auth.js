import axios from "axios";
import router from "../../router";

export const auth = {
    namespaced: true,

    state: {
        isLoggedIn: null,
        user: {},
        errorMessage: null
    },

    getters: {
        getIsLoggedIn: state => state.isLoggedIn,
        getUser: state => state.user,
        getError: state => state.errorMessage
    },

    actions: {
        async setUser({ commit }) {
            const { data } = await axios.get("/api/user");

            if (data) {
                commit("SET_LOGGEDIN", true);
                commit("SET_USER", data);
            } else {
                commit("SET_LOGGEDIN", false);
            }
        },

        async edit({ dispatch }, payload) {
            await axios.put("/api/user/", payload);
            await dispatch("setUser");
            router.go();
        },

        async login({ dispatch }, payload) {
                await axios.post("/api/login", payload)

                dispatch("setUser");
                router.push({ name: "ad.overview" });
        },

        async logout({ commit}) {
            await axios.get("/api/logout");
            commit("SET_LOGGEDIN", false);
            router.push({ name: "ad.overview" }).catch(err => {
                if (
                  err.name !== 'NavigationDuplicated' &&
                  !err.message.includes('Avoided redundant navigation to current location')
                ) {
                  logError(err);
                }
              });
        },

        async register({ dispatch }, payload) {
            await axios.post("/api/register", payload);

            dispatch("setUser");
            router.push({ name: "ad.overview" });
        },

        async sendEmail({commit}, payload) {
            await axios.post("/api/forgot-password", payload);
        },

        async resetPassword({ commit }, payload) {
                await axios.post("/api/reset-password", payload);

                router.push({ name: "auth.login" });
        },
    },

    mutations: {
        SET_LOGGEDIN: (state, payload) => {
            state.isLoggedIn = payload;
        },

        SET_USER(state, payload) {
            state.user = payload[0];
        },

        SET_ERROR(state, payload) {
            state.errorMessage = payload;
        },

        CLEAR_ERROR(state) {
            state.errorMessage = null;
        }
    }
};