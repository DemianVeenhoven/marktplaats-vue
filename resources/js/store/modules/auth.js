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
            await axios.put("/api/user/" + payload.id, payload);
            await dispatch("setUser");
            router.go();
        },

        async login({ commit, state, dispatch }, payload) {
                const {status} = await axios.post("/api/login", payload)
                    .then(()=> console.log('then'))
                    .catch(()=> console.log('catch'))

                console.log(status)

                // if(!state.errorMessage) {
                //     dispatch("setUser");
                //     router.push({ name: "ad.overview" });
                // }
        },

        async logout({ commit, dispatch }) {
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

        async register({ commit, dispatch }, payload) {
            return new Promise((resolve, reject) => {
                axios.post("/api/register", payload)
                    .then((response) => {
                        resolve(response)
                        commit("SET_LOGGEDIN", true);
                        dispatch("setUser");
                        router.push({ name: "ad.overview" });
                    })
                    .catch((err) => {
                        const errMes = err.response.data.message;

                        if (err.response.data.errors.email[0].includes("The email has already been taken")) {
                            commit("SET_ERROR", "That email is already in use.");
                        } else if (!errMes.includes("for key 'users_name_unique'")) {
                            commit("SET_ERROR", errMes);
                        } else {
                            commit("SET_ERROR", "That username is already in use.");
                        }
                    })
            }) 
        },

        async sendEmail({commit}, payload) {
            return new Promise((resolve, reject) => {
                axios.post("/api/forgot-password", payload)
                    .then((response) => {
                        resolve(response)
                    })
                    .catch((err) => {
                        commit("SET_ERROR", err.response.data.message)
                    })
            })
        },

        async resetPassword({ commit, dispatch }, payload) {
            return new Promise((resolve, reject) => {
                axios.post("/api/reset-password", payload)
                    .then((response) => {
                        resolve(response)
                        router.push({ name: "auth.login" });
                    })
                    .catch((err) => {
                        commit("SET_ERROR", err.response.data.message)
                    })
            }) 
        },
    },

    mutations: {
        SET_LOGGEDIN: (state, payload) => {
            state.isLoggedIn = payload;
        },

        SET_USER(state, payload) {
            state.user = payload;
        },

        SET_ERROR(state, payload) {
            state.errorMessage = payload;
        },

        CLEAR_ERROR(state) {
            state.errorMessage = null;
        }
    }
};