import axios from "axios";
import router from "../../router";

export default {
    namespaced: true,

    state: {
        messageChains: [],
        messages: []
    },

    getters: {
        getMessageChainsByUser: (state) => (user_id) => {
            return state.messageChains.filter(
                item => 
                    item.advertiser_id === user_id ||
                    item.bidder_id === user_id
            )
        },

        getSingleMessageChain: (state) => (chain_id) => {
            return state.messageChains.find(item => item.id === chain_id)
        },

        getMessagesByChain: (state) => (chain_id) => {
            return state.messages.filter(item => item.chain_id === chain_id)
        },
    },

    actions: {
        async setMessageChains({commit}) {
            const {data} = await axios.get("/api/message_chain");
            commit("SET_MESSAGECHAINS", data);
        },

        async setMessages({commit}) {
            const {data} = await axios.get("/api/message");
            commit("SET_MESSAGES", data);
        },

        async createMessageChain({commit}, payload) {
            const {data} = await axios.post("/api/message_chain", payload);
            router.push({name: "messageChain", params: {id: data}});
        },

        async createMessage({commit}, payload) {
            await axios.post("/api/message", payload.new_message);
            await axios.post("/api/message/send_notification", payload.emailInfo);
            // router.go();
        },

        async deleteMessageChain({commit, dispatch}, payload) {
            await axios.delete("/api/message_chain/" + payload);
            dispatch("setMessageChains");
            router.push({ name: "allMessageChains" }).catch(err => {
                if (
                  err.name !== 'NavigationDuplicated' &&
                  !err.message.includes('Avoided redundant navigation to current location')
                ) {
                  logError(err);
                }
              });
        }
    },

    mutations: {
        SET_MESSAGECHAINS(state, payload) {
            state.messageChains = payload;
        },

        SET_MESSAGES(state, payload) {
            state.messages = payload;
        }
    }
}