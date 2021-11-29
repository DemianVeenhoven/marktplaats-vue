<template>
    <div>
        <h1>{{ getAccount.name }}</h1>

        <div>
            <h2>My ads</h2>

            <div v-for="ad in getAds" :key="ad.id" class="ad">
                
                <div>
                <h2>{{ad.title}}</h2>

                <p>{{ad.description}}</p>

                <img :src="ad.image">

                <p>Placed by: {{ad.advertiser}}</p>

                <p>Placed on: {{ad.created_at}}</p> 
            </div>

            <div>
                <router-link :to="{ name: 'ad.edit', params: {id: ad.id}}">
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
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
   data() {
       return {
           account: {},

           myAds: []
       }
   },

   computed: {
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
            if (this.getAccount.id) {
                return this.$store.getters["ads/getAdsByUser"](this.getAccount.id);
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