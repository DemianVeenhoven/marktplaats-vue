<template>
    <div>
        <h1>{{ getAccount.name }}</h1>

        <div>
            <h2>My ads</h2>
            
            <div v-if="!getAds.length">
                <p>You do not have any active ads.</p>
            </div>

            <div v-else v-for="ad in getAds" :key="ad.id">
                <b-card
                    :title="ad.title"
                    :img-src="ad.image"
                    style="max-width: 20rem;"
                >
                    <b-card-text>{{ad.description}}</b-card-text>

                    <b-card-text>Placed by: {{ad.advertiser}}</b-card-text>

                    <b-card-text>Placed on: {{ad.created_at}}</b-card-text>

                    <b-button 
                        :to="{ name: 'ad.edit', params: {id: ad.id}}" 
                        variant="primary"
                    >Edit</b-button>

                    <b-card-text>
                        categories

                        <div v-for="category in ad.categories" :key="category.id" class="categories">
                            {{category.name}}
                        </div>
                    </b-card-text>

                    <b-button 
                        :to="{ name: 'messageChain', params: {id: activeMessageChain(ad.id)}}"
                        variant="primary"
                    >Show message chain</b-button>

                    <b-card-text v-if="ad.bids.length">
                        Bids:

                        <div  v-for="bid in ad.bids" :key="bid.id" class="bids">
                            €{{bid.amount}}
                            By: {{bid.bidder}}
                            Placed on: {{bid.created_at}}
                        </div>
                    </b-card-text>
                </b-card>

                <br>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
   data() {
       return {
           account: {}
       }
   },

   computed: {
       ...mapGetters({
           user: "auth/getUser"
       }),

       getAccount() {
            let myAccount = this.$store.getters["auth/getUser"];

            if (myAccount) {
                this.account = JSON.parse(JSON.stringify(myAccount));

                return myAccount;
            } else {
                return {};
            }
        },

        getAds() {
            const myAds = this.$store.getters["ads/getAdsByUser"](this.getAccount.id);

            if(!myAds) {
                return []
            } else {
                return myAds
            }
        }
   },

   methods: {
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

    .categories {
        border-style: solid;
    }
</style>