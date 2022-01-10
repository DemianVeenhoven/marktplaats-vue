<template>
    <div>
        <h1>My message chains</h1>
        
        <div v-if="!getMessageChains.length">
            <p>You do not have any ongoing message chains.</p>
        </div>

        <div v-else v-for="chain in getMessageChains" :key="chain.id">
            <b-card
                :title="chain.ad"
                style="max-width: 20rem;"
            >
                <b-card-text>Advertiser: {{chain.advertiser}}</b-card-text>

                <b-card-text>Bidder: {{chain.bidder}}</b-card-text>
                
                <b-card-text>Last message:</b-card-text>

                <div class="message">
                    <b-card-text>{{lastMessage(chain.id).body}}</b-card-text>
                    <b-card-text>By: {{lastMessage(chain.id).writer}}</b-card-text>
                    <b-card-text>Written on: {{lastMessage(chain.id).created_at}}</b-card-text>
                </div>

                <br>

                <b-button :to="{ name: 'messageChain', params: {id: chain.id}}" variant="success">Go to message chain</b-button>

                <b-button 
                    v-if="chain.advertiser_id == user.id" 
                    @click="deleteChain(chain.id)" 
                    variant="danger"
                >Delete</b-button>
            </b-card>
            
            <br>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    created() {
        this.$store.dispatch("messages/setMessageChains");
        this.$store.dispatch("messages/setMessages");
    },

    computed: {
        ...mapGetters({
            user: "auth/getUser"
        }),

        getMessageChains() {
            if (this.user.id) {
                return this.$store.getters["messages/getMessageChainsByUser"](this.user.id);
            }

            return [];
        }
    },

    methods: {
        lastMessage(chain_id) {
            const messages = this.$store.getters["messages/getMessagesByChain"](chain_id);
            const lastIndex = messages.length - 1

            if (messages) {
                messages[lastIndex];
            } 

            return {};
        },

        deleteChain(chain_id) {
            this.$store.dispatch("messages/deleteMessageChain", chain_id);
        }
    }
}
</script>

<style scoped>
    .message {
        border-style: solid;
    }
</style>