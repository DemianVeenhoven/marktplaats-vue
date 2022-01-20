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
                    v-if="totalAds > adsPerPage"
                    v-model="currentPage"
                    :total-rows="totalAds"
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

                <b-button @click="clearCategoryFilter()">Clear filter</b-button>
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

            <cards :ads = "ads"></cards>

            <div>
                <b-pagination
                    v-if="totalAds > adsPerPage"
                    v-model="currentPage"
                    :total-rows="totalAds"
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
import cards from "./Overview-cards.vue"

export default {
    components: {
        Multiselect, cards
    },

    data() {
        return {
            currentPage: 1,

            adsPerPage: 5,

            totalAds: null,

            loadingComplete: false,

            //filters data
            multiselectArray: [],

            categoryFilter: [],

            searchBar: null,

            distance: null,
        }
    },

    computed: {
        ...mapGetters ({
            allAds: "ads/getAll",
            categories: "categories/getAll",
            user: "auth/getUser"
        }),

        ads() {
            let ads = this.allAds;

            if (this.categoryFilter) {
                ads = ads.filter(this.hasCategories(this.categoryFilter));
            }

            if (this.searchBar) ads = this.search(ads);

            if (this.distance) {
                ads = this.checkDistance(ads, this.distance);
            }

            this.totalAds = ads.length;

            return this.sliceAds(ads);
        },
    },

    methods: {
        setPageNumber(pageNumber) {
            this.currentPage = pageNumber;
        },

        setAdsPerPage(amountOfAdsPerPage) {
            this.adsPerPage = amountOfAdsPerPage;
        },
        
        adsLoaded() {
            setTimeout(() => this.loadingComplete = true, (this.adsPerPage * 400));
        },

        setCategoryFilter() {
            if (this.multiselectArray) {
                this.categoryFilter = [];
                this.multiselectArray.forEach(item => this.categoryFilter.push(item.id));
            }
        },

        clearCategoryFilter() {
            this.multiselectArray = [];
            this.setCategoryFilter = [];
        },  
        
         hasCategories(categories) {
            return function(element) {
                return element.categories.filter(category => categories.includes(category.id)).length == categories.length 
            }
        },

        search(searchbarText) {
            return searchbarText.filter(ad => ad.title.toLowerCase().includes(this.searchBar.toLowerCase()) || ad.description.toLowerCase().includes(this.searchBar.toLowerCase()));
        },

        //distance filter
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
        },

        //return filtered ads
        sliceAds(array) {
            const start = (this.currentPage - 1) * this.adsPerPage;
            const end =  start + this.adsPerPage;

            this.adsLoaded();

            return array.slice(start, end);
        },
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