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
                    v-if="!searchBar && !categoryFilter.length"
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
                                <b-card-img v-if="ad.image != '/storage/'" :src="ad.image" top></b-card-img>

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
                    v-model="currentPage"
                    :total-rows="allAds.length"
                    :per-page="adsPerPage"
                    aria-controls="ads"
                ></b-pagination>
            </div>

        </b-overlay>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
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

        ads() {
            // TODO :: refactor. No need for all the if elses. No need for the duplications
            if (this.categoryFilter.length) {
                function hasCategories(categories) {
                    return function(element) {
                        return element.categories.filter(category => categories.includes(category.id)).length == categories.length
                    }
                }

                const filteredAds = this.allAds.filter(hasCategories(this.categoryFilter));

                if (this.searchBar) {
                    // return searched category filtered ads
                    const searchResults = this.search(filteredAds)

                    return this.sliceAds(searchResults);
                } else {
                    // return category filtered ads
                    return this.sliceAds(filteredAds);
                }
            } else {
                if (this.searchBar) {
                    // return searched ads
                    const searchResults = this.search(this.allAds)

                    return this.sliceAds(searchResults);
                } else {
                    // return all ads
                    return this.sliceAds(this.allAds);
                }
            }
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
            // TODO :: refactor, no need for the else
            if (this.multiselectArray.length) {
                this.categoryFilter = [];
                this.multiselectArray.forEach(item => this.categoryFilter.push(item.id));
            } else {
                this.categoryFilter = [];
            }
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

            // TODO :: refactor, no need for the else
            if (activeChain) {
                return activeChain.id;
            } else {
                return false;
            }
        },

        adsLoaded() {
            // TODO :: use arrow function, then no need for the bind this
            setTimeout(function() {this.loadingComplete = true;}.bind(this), (this.adsPerPage * 400));
        },

        sliceAds(array) {
            const start = (this.currentPage - 1) * this.adsPerPage;
            const end =  start + this.adsPerPage;

            this.adsLoaded();

            return array.slice(start, end);
        },

        search(payload) {
            return payload.filter(ad => ad.title.toLowerCase().includes(this.searchBar.toLowerCase()) || ad.description.toLowerCase().includes(this.searchBar.toLowerCase()));
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
