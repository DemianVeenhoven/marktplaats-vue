<template>
    <div>
        <b-overlay :show="!loadingComplete">
            <h1>{{ getAccount.name }}</h1>

            <div>
                <h2>My ads</h2>

                <div id="myAccount">
                    <div>
                        <label>Username:</label>
                        <input type="text" id="name" v-model="account.name"/>
                    </div>

                    <br>

                    <!-- <div>
                        <label>Postalcode:</label>
                        <input type="text" id="name" v-model="account.name"/>

                        <b-button @click="changePostalCode()" variant="primary">Change postalcode</b-button>
                    </div>

                    <br> -->

                    <div>
                        <label>Receive message notifications:</label>
                        <input type="checkbox" id="name" v-model="account.getMessageNotifications"/>
                    </div>

                    <br>

                    <b-button @click="editUser()" variant="primary">Edit account</b-button>
                </div>

                <hr>
                
                <div id="myAds">
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

                            <b-button
                                v-if="!ad.premium"
                                :to="{ name: 'ad.premium', params: {id: ad.id}}" 
                                variant="primary"
                            >Make ad premium</b-button>

                            <b-card-text>
                                categories

                                <div v-for="category in ad.categories" :key="category.id" class="categories">
                                    {{category.name}}
                                </div>
                            </b-card-text>

                            <b-button 
                                v-if="activeMessageChain(ad.id)" 
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
        </b-overlay>
    </div>
</template>

<script>
export default {
   data() {
       return {
           account: {},

           loadingComplete: false
       }
   },

   computed: {
       getAccount() {
            const myAccount = this.$store.getters["auth/getUser"][0];

            if (myAccount) {
                this.account = JSON.parse(JSON.stringify(myAccount));

                return myAccount;
            } 

            return {};
        },

        getAds() {
            const myAds = this.$store.getters["ads/getAdsByUser"](this.getAccount.id);

            if(myAds) {
                setTimeout(() => this.loadingComplete = true, (this.adsPerPage * 400));

                return myAds;
            } 

            return [];
        }
   },

   methods: {
        editUser() {
            if (this.account.name == this.getAccount.name) {
                vue.delete(this.account, "name");
            }

            this.$store.dispatch("auth/edit", this.account);
        },

        activeMessageChain(payload) {
            const userMessageChains = this.$store.getters["messages/getMessageChainsByUser"](this.account.id)
            const activeChain = userMessageChains.find(item => item.ad_id === payload);

            if (activeChain) {
                return activeChain.id;
            } 

            return false;
        },
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