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
            return state.ads.find(ad => ad.id === ad_id)
        },

        getAdsByUser: (state) => (user_id) => {
            return state.ads.filter(ad => ad.advertiser_id === user_id);
        }
    },

    actions: {
        async setAll({commit}) {
            const {data} = await axios.get("/api/ads");
            commit("SET_ALL", data);
        },

        async create({commit, dispatch}, payload) {
            await axios.post("/api/ads", payload);
            dispatch("setAll");
            router.push({name: "ad.overview"});
        },

        async edit({commit, dispatch}, payload) {
            await axios.put("/api/ads/" + payload.id, payload);
            dispatch("setAll");
            router.push({name: "ad.overview"});
        },

        async remove({commit, dispatch}, payload) {
            await axios.delete("/api/ads/" + payload);
            dispatch("setAll");
            router.push({name: "ad.overview"});
        },

        async bid({commit, dispatch}, payload) {
            await axios.post("/api/ads/" + payload.ad_id + "/bid", payload);
            dispatch("setAll");
            router.push({name: "ad.overview"});
        },

        async upgrade({commit, dispatch}, payload) {
            await axios.get("/api/ads/" + payload + "/upgrade")
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