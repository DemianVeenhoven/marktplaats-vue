<template>
    <div>
        <h1 v-if="getMessageChain">Messages about {{getMessageChain.ad}}</h1>

        <div>
            <label>New message:</label>

            <br>

            <textarea id="body" v-model="new_message.body"></textarea>

            <br>

            <div>
                <b-button @click="sendMessage()" variant="primary">Send</b-button>
                <b-button 
                    v-if="getMessageChain.advertiser_id == user.id"
                    @click="deleteChain()"
                    variant="danger"
                >Delete chain</b-button>
            </div>
        </div>

        <br>

        <div v-for="message in getMessages" :key="message.id">
            <div :class="{
                    advertiser: message.writer_id == getMessageChain.advertiser_id,
                    bidder: message.writer_id == getMessageChain.bidder_id,
                    message
            }">
                <p>{{message.body}}</p>
                <p>By: {{message.writer}}</p>
                <p>Written on: {{message.created_at}}</p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    created() {
        this.$store.dispatch("messages/setMessageChains");
        this.$store.dispatch("messages/setMessages");
    },

    data() {
        return {
            new_message: {
                body: null,
                chain_id: null
            }
        }
    },

    computed: {
        ...mapGetters({
            user: "auth/getUser"
        }),

        messageChainId() {
            const chain_id = parseInt(this.$route.params.id);
            this.new_message.chain_id = chain_id;
            return chain_id;
        },

        getMessageChain() {
            const messageChain = this.$store.getters["messages/getSingleMessageChain"](this.messageChainId)

            if(messageChain) {
                return messageChain;
            }

            return {};
        },

        getMessages() {
            const messages = this.$store.getters["messages/getMessagesByChain"](this.messageChainId);

            if (messages) {
                return messages;
            }

            return [];
        }
    },

    methods: {
        sendMessage() {
            const payload = {};

            if (this.user.id == this.getMessageChain.advertiser_id) {
               payload.emailInfo = {
                    messageUrl: "http://127.0.0.1:8000/message_chain/" + this.getMessageChain.id,
                    messageSenderName: this.getMessageChain.advertiser,
                    messageReceiverName: this.getMessageChain.bidder,
                    messageReceiverEmail: this.getMessageChain.bidder_email
               };
            } else {
               payload.emailInfo = {
                    messageChainUrl: "http://127.0.0.1:8000/message_chain/" + this.getMessageChain.id,
                    messageSenderName: this.getMessageChain.bidder,
                    messageReceiverName: this.getMessageChain.advertiser,
                    messageReceiverEmail: this.getMessageChain.advertiser_email
               };
            }

            payload.new_message = this.new_message;

            this.$store.dispatch("messages/createMessage", payload);
        },

        deleteChain() {
            this.$store.dispatch("messages/deleteMessageChain", this.messageChainId);
        }
    }
}
</script>

<style scoped>
    .message {
        border-style: solid;
    }

    .bidder {
        background-color: aquamarine;
    }

    .advertiser {
        background-color: burlywood;
    }
</style>