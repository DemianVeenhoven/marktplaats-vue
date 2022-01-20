import axios from "axios";
import router from "../../router";

export default {
    namespaced: true,

    state: {
        categories: []
    },

    getters: {
        getAll: state => state.categories
    },

    actions: {
        async setAll({commit}) {
            const {data} = await axios.get("/api/categories")
            commit("SET_ALL", data)
        },

        async create({dispatch}, payload) {
            const {data} = await axios.post("/api/categories", payload);
            dispatch("setAll");
            return data;
        }
    },

    mutations: {
        SET_ALL(state, payload) {
            state.categories = payload;
        }
    }
}