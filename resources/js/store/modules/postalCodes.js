import axios from "axios";
import router from "../../router";

export default {
    namespaced: true,

    state: {
        postalCodes: []
    },

    getters: {
        getAll: state => state.postalCodes,
    },

    actions: {
        async setAll({commit}) {
            const {data} = await axios.get("/api/postalCodes");
            commit("SET_ALL", data);
        },
    },

    mutations: {
        SET_ALL(state, payload) {
            state.postalCodes = payload;
        }
    }
}