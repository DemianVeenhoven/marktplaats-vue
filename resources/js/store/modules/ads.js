import axios from "axios";
import router from "../../router";

export default {
    namespaced: true,

    state: {
        ads: []
    },

    getters: {
        getAll: state => state.ads,

        getSingleAd: (state) => (ad_id) => {
            return state.ads.find(item => item.id === ad_id)
        },

        getAdsByUser: (state) => (user_id) => {
            return state.ads.filter(item => item.advertiser_id === user_id);
        }
    },

    actions: {
        async setAll({commit}) {
            const {data} = await axios.get("/api/ads");
            commit("SET_ALL", data.data);
        },

        async create({commit}, payload) {
            const {data} = await axios.post("/api/ads", payload);
            commit("SET_ALL", data.data);
            router.push({name: "ad.overview"});
        },

        async edit({commit}, payload) {
            const {data} = await axios.put("/api/ads/" + payload.id, payload);
            commit("SET_ALL", data.data);
            router.push({name: "ad.overview"});
        },

        async remove({commit}, payload) {
            const {data} = await axios.delete("/api/ads/" + payload);
            commit("SET_ALL", data.data);
            router.push({name: "ad.overview"});
        },

        async filterCategories({commit, dispatch}, payload) {
            if (payload != 0) {
                const {data} = await axios.post("/api/ads/filter/categories/" + payload)
                commit("SET_ALL", data.data);
                router.push({name: "ad.overview"});
            } else {
                dispatch("setAll");
            }
        },

        async bid({commit, dispatch}, payload) {
            await axios.post("/api/ads/" + payload.ad_id + "/bid", payload);
            dispatch("setAll");
            router.push({name: "ad.overview"});
        }
    },

    mutations: {
        SET_ALL(state, payload) {
            state.ads = payload;
        }
    }
}