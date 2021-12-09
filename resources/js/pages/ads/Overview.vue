<template>
    <div class="page">
        <b-overlay>
        <h1>Ads</h1>

        <div>
            <p>Ads per page</p>
            <button @click="setAdsPerPage(5)">5</button>
            <button @click="setAdsPerPage(10)">10</button>
            <button @click="setAdsPerPage(20)">20</button>
        </div>

        <br>

        <div class="page_numbers">
            <p>Pages:</p>
            <div v-for="index in amountOfPages" :key="index" class="page_number">
                <button @click="setPageNumber(index)">{{index}}</button>
            </div>
        </div>

        <br>

        <div class="filter_categories">
            <b-dd v-model="filterId" text="Select a category">
                <b-dropdown-item @click="filterCategories(0)">All categories</b-dropdown-item>

                <b-dropdown-divider></b-dropdown-divider>

                <b-dropdown-item 
                    v-for="category in categories" 
                    :key="category.id"
                    @click="filterCategories(category.id)"
                >{{ category.name }}</b-dropdown-item>
            </b-dd>
        </div>

        <br>

        <div v-for="ad in getAds" :key="ad.id">
            <b-card
                :title="ad.title"
                :img-src="ad.image"
                style="max-width: 20rem;"
            >
                <b-card-text>{{ad.description}}</b-card-text>

                <b-card-text>Placed by: {{ad.advertiser}}</b-card-text>

                <b-card-text>Placed on: {{ad.created_at}}</b-card-text>

                <b-button 
                    v-if="ad.advertiser_id == user.id"
                    :to="{ name: 'ad.edit', params: {id: ad.id}}" 
                    variant="primary"
                >Edit</b-button>

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
        </div>
        </b-overlay>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    created() {
        this.$store.dispatch("messages/setMessageChains")
    },

    data() {
        return {
            pageNumber: 1,

            adsPerPage: 5,

            filterId: null,

            bid: {
                amount: 0,
                bidder: null,
                ad: null
            },

            messageChain: {
                ad_id: null,
                bidder_id: null
            },
        }
    },

    computed: {
        ...mapGetters ({
            allAds: "ads/getAll",
            user: "auth/getUser",
            categories: "categories/getAll"
        }),

        amountOfPages() {
            const quotient = Math.floor(this.allAds.length/this.adsPerPage);
            const remainder = this.allAds.length % this.adsPerPage == 0;
            let amount = 0;

            if (remainder) {
                return amount = quotient;
            } else {
                return amount = quotient + 1;
            }
        },

        getAds() {
            const start = (this.pageNumber - 1) * this.adsPerPage;
            const end =  start + this.adsPerPage;
            
            return this.allAds.slice(start, end);
        },

        // getAdsByCategory() {
        //     if (!this.filterId) {
        //         return this.getAds
        //     } else {
        //         const filteredAds = this.allAds.filter(
        //             item => item.categories.id === this.filterId
        //         )

        //         const start = (this.pageNumber - 1) * this.adsPerPage;
        //         const end =  start + this.adsPerPage;

        //         return this.filteredAds.slice(start, end);
        //     }
        // }
    },

    methods: {
        setPageNumber(payload) {
            this.pageNumber = payload;
        },

        setAdsPerPage(payload) {
            this.adsPerPage = payload;
        },

        filterCategories(payload) {
            // this.$store.dispatch("ads/filterCategories", this.filterId)
            this.filterId = payload;
        },

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
            } else {
                return false;
            }
        }   
    }
}
</script>

<style scoped>
    .ad {
        border-style: solid;
    }

    .page_number {
        display: inline-block;
    }
</style>