<template>
    <div id="ads">
        <b-container fluid>
            <b-row cols="1" cols-sm="2" cols-md="3" cols-lg="4">
                <b-col
                    v-for="ad in ads"
                    :key="ad.id"
                    col
                    no-gutters
                    class="mb-2"
                >
                    <b-card
                        :title="ad.title"
                        style="max-width: 20rem;"
                    >
                        <b-card-img v-if="ad.image_path != '/storage/'" :src="ad.image_path" top></b-card-img>

                        <b-card-text>{{ad.description}}</b-card-text>

                        <b-card-text>Placed by: {{ad.advertiser}}</b-card-text>

                        <b-card-text>Placed on: {{ad.created_at}}</b-card-text>

                        <b-button 
                            v-if="ad.advertiser_id == user.id"
                            :to="{ name: 'ad.edit', params: {id: ad.id}}" 
                            variant="primary"
                        >Edit</b-button>

                        <b-button
                            v-if="ad.advertiser_id == user.id && !ad.premium"
                            :to="{ name: 'ad.premium', params: {id: ad.id}}" 
                            variant="success"
                        >Upgrade to premium</b-button>

                        <b-card-text>
                            categories:

                            <b-list-group v-for="category in ad.categories" :key="category.id" class="categories">
                                <b-list-group-item>{{category.name}}</b-list-group-item>
                            </b-list-group>
                        </b-card-text>
                        <!-- I don't think this bid button is a good solution -->
                        <b-button 
                            v-if="ad.advertiser_id != user.id && user.id != null" 
                            :to="{ name: 'ad.bid', params: {id: ad.id}}" 
                            variant="primary"
                        >Place a bid</b-button>

                        <b-button 
                            v-if="ad.advertiser_id != user.id && user.id != null && !activeMessageChain(ad.id)" 
                            @click="newMessageChain(ad)"
                            variant="primary"
                        >Message advertiser</b-button>

                        <b-button 
                            v-if="activeMessageChain(ad.id)" 
                            :to="{ name: 'messageChain', params: {id: activeMessageChain(ad.id)}}"
                            variant="primary"
                        >Show message chain</b-button>

                        <b-card-text>
                            Bids:

                            <b-list-group  v-for="bid in ad.bids" :key="bid.id" class="bids">
                                <b-list-group-item>
                                    €{{bid.amount}} 
                                    By: {{bid.bidder}}
                                    Placed on: {{bid.created_at}}
                                </b-list-group-item>
                            </b-list-group>
                        </b-card-text>
                    </b-card>

                    <br>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    props: [
        "ads"
    ],

    created() {
        this.$store.dispatch("messages/setMessageChains")
    },

    data() {
        return {
            messageChain: {
                ad_id: null,
                bidder_id: null
            },

            bid: {
                amount: 0,
                bidder: null,
                ad: null
            },
        }
    },

    computed: {
        ...mapGetters ({
            categories: "categories/getAll",
            user: "auth/getUser"
        }),
    },

    methods: {
        newMessageChain(payload) {
            this.messageChain.ad_id = payload.id;
            this.messageChain.bidder_id = this.user.id;

            this.$store.dispatch("messages/createMessageChain", this.messageChain);
        },

        activeMessageChain(payload) {
            const userMessageChains = this.$store.getters["messages/getMessageChainsByUser"](this.user.id)
            const activeChain = userMessageChains.find(item => item.ad_id === payload);

            if (activeChain) {
                return activeChain.id;
            } 

            return false;
        },
    }
}
</script>