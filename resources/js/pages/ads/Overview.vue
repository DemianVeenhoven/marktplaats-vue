<template>
    <div class="page">
        <b-overlay :show="!loadingComplete">
            <h1>Ads</h1>

            <div>
                <b-form-input type="search" v-model="searchBar" placeholder="Search"></b-form-input>
                
                <br>

                <b-button @click="searchBar = null" variant="primary">Clear searchbar</b-button>
            </div>

            <br>

            <div>
                <b-button-group>
                    <b-button @click="setAdsPerPage(5)" variant="primary">5</b-button>
                    <b-button @click="setAdsPerPage(10)" variant="primary">10</b-button>
                    <b-button @click="setAdsPerPage(20)" variant="primary">20</b-button>
                </b-button-group>
            </div>

            <br>

            <div>
                <b-pagination
                    v-if="!searchBar && !categoryFilter.length && !distance"
                    v-model="currentPage"
                    :total-rows="allAds.length"
                    :per-page="adsPerPage"
                    aria-controls="ads"
                ></b-pagination>

                <b-pagination
                    v-else
                    v-model="currentPage"
                    :total-rows="ads.length"
                    :per-page="adsPerPage"
                    aria-controls="ads"
                ></b-pagination>
            </div>

            <br>

            <div class="filter_categories">
                <multiselect
                    v-model="multiselectArray" 
                    :options="categories" 
                    :multiple="true" 
                    :close-on-select="false" 
                    :clear-on-select="false" 
                    placeholder="Filter by categories" 
                    label="name" 
                    track-by="id" 
                    :preselect-first="false" 
                    :searchable="false"
                    @input="setCategoryFilter"
                ></multiselect>

                <br>

                <b-button @click="clearFilter()">Clear filter</b-button>
            </div>

            <br>

            <div>
                <b-form-select v-model="distance" class="mb-3">
                    <b-form-select-option :value="null">No distance limit</b-form-select-option>
                    <b-form-select-option :value="5">5 km</b-form-select-option>
                    <b-form-select-option :value="10">10 km</b-form-select-option>
                    <b-form-select-option :value="25">25 km</b-form-select-option>
                    <b-form-select-option :value="50">50 km</b-form-select-option>
                    <b-form-select-option :value="75">75 km</b-form-select-option>
                    <b-form-select-option :value="100">100 km</b-form-select-option>
                </b-form-select>
            </div>

            <br>

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

            <div>
                <b-pagination
                    v-if="!searchBar && !categoryFilter.length && !distance"
                    v-model="currentPage"
                    :total-rows="allAds.length"
                    :per-page="adsPerPage"
                    aria-controls="ads"
                ></b-pagination>

                <b-pagination
                    v-else
                    v-model="currentPage"
                    :total-rows="ads.length"
                    :per-page="adsPerPage"
                    aria-controls="ads"
                ></b-pagination>
            </div>

        </b-overlay>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
// import { filter } from 'vue/types/umd';
import { mapGetters } from "vuex";

export default {
    components: {
        Multiselect
    },

    created() {
        this.$store.dispatch("messages/setMessageChains")
    },
    data() {
        return {
            currentPage: 1,

            adsPerPage: 5,

            loadingComplete: false,

            categoryFilter: [],

            multiselectArray: [],

            searchBar: null,

            distance: null,

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
            categories: "categories/getAll"
        }),

        user() {
            return this.$store.getters["auth/getUser"][0];
        },

        ads() {
            let ads = this.allAds;

            if (this.categoryFilter.length) {
                ads = ads.filter(this.hasCategories(this.categoryFilter));
            }

            if (this.searchBar) ads = this.search(ads);

            if (this.distance) {
                ads = this.checkDistance(ads, this.distance);
            }
            return this.sliceAds(ads);
        },
    },

    methods: {
        setPageNumber(payload) {
            this.currentPage = payload;
        },

        setAdsPerPage(payload) {
            this.adsPerPage = payload;
        },

        setCategoryFilter() {
            if (this.multiselectArray.length) {
                this.categoryFilter = [];
                this.multiselectArray.forEach(item => this.categoryFilter.push(item.id));
            } 

            this.categoryFilter = [];
        },

        clearFilter() {
            this.multiselectArray = []
            this.setCategoryFilter();
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
            } 

            return false;
        },
        
        adsLoaded() {
            setTimeout(() => this.loadingComplete = true, (this.adsPerPage * 400));
        },

        sliceAds(array) {
            const start = (this.currentPage - 1) * this.adsPerPage;
            const end =  start + this.adsPerPage;

            this.adsLoaded();

            return array.slice(start, end);
        },

        hasCategories(categories) {
            return function(element) {
                return element.categories.filter(category => categories.includes(category.id)).length == categories.length 
            }
        },

        search(payload) {
            return payload.filter(ad => ad.title.toLowerCase().includes(this.searchBar.toLowerCase()) || ad.description.toLowerCase().includes(this.searchBar.toLowerCase()));
        },

        calcDistance(adLat, adLon) {
            const R = 6371; // Radius of the earth in km
            const userLat = this.user.postalCode[0].latitude;
            const userLon = this.user.postalCode[0].longitude; 

            const dLat = this.deg2rad(adLat-userLat);  // deg2rad below
            const dLon = this.deg2rad(adLon-userLon); 

            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(this.deg2rad(userLat)) * Math.cos(this.deg2rad(adLat)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            const d = R * c; // Distance in km
            return d;
        },

        deg2rad(deg) {
            return deg * (Math.PI/180)
        },

        checkDistance(array, distance) {
            const adsInRange = [];

            array.forEach(element => {
                if(element.postalCode[0]) {
                    let adLat = element.postalCode[0].latitude;
                    let adLon = element.postalCode[0].longitude;

                    if (this.calcDistance(adLat, adLon) <= distance) {
                        adsInRange.push(element);
                    }
                }
            });

            return adsInRange;
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