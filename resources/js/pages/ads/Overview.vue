<template>
    <div>
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
            <select v-model="filterId" @change="filterCategories()">
                <option disabled value="null">Please select a category</option>
                <option :value="0">All categories</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                    >{{ category.name }}</option
                >
            </select>
        </div>

        <br>

        <div v-for="ad in getAds" :key="ad.id">
            <div class="ad">
                <div>
                    <h2>{{ad.title}}</h2>

                    <p>{{ad.description}}</p>

                    <img :src="ad.image">

                    <p>Placed by: {{ad.advertiser}}</p>

                    <p>Placed on: {{ad.created_at}}</p> 
                </div>

                <div v-if="ad.advertiser_id == user.id">
                    <router-link :to="{ name: 'ad.edit', params: {id: ad.id}}" tag="button">
                        Edit
                    </router-link>
                </div>

                <div>
                    <p>Categories:</p>

                    <div v-for="category in ad.categories" :key="category.id" class="categories">
                        <p>{{category.name}}</p>
                    </div>
                </div>

                <br>

                <div v-if="ad.advertiser_id != user.id">
                    <!-- I don't think this is a good solution  -->
                    <router-link :to="{ name: 'ad.bid', params: {id: ad.id}}" tag="button">
                        Place a bid
                    </router-link>
                </div>
                
                <div v-for="bid in ad.bids" :key="bid.id">
                    <p>Bids:</p>

                    <div  class="bids">
                        <p>€{{bid.amount}}</p>
                        <p>By: {{bid.bidder}}</p>
                        <p>Placed on: {{bid.created_at}}</p>
                    </div>
                </div>
            </div>

            <br>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            pageNumber: 1,

            adsPerPage: 5,

            filterId: null,

            bid: {
                amount: 0,
                bidder: null,
                ad: null
            }
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
        }
    },

    methods: {
        setPageNumber(payload) {
            this.pageNumber = payload;
        },

        setAdsPerPage(payload) {
            this.adsPerPage = payload;
        },

        filterCategories() {
            this.$store.dispatch("ads/filterCategories", this.filterId)
        }
    }
}
</script>

<style scoped>
    .ad {
        border-style: solid;
    }

    .categories {
        border-style: solid;
    }

    .bids {
        border-style: solid;
    }

    .page_number {
        display: inline-block;
    }
</style>