<template>
    <div>
        <h1>Place a bid</h1>

        <div>
            <label for="bid_amount">€</label>
            <input type="text" name="bid_amount" v-model="bid.amount" @keypress="onlyForCurrency($event)">

            <br>

            <button v-on:click="submitBid(adId)">Place bid</button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            bid: {
                amount: 0,
                ad_id: null
            }
        }
    },

    computed: {
        adId() {
            return parseInt(this.$route.params.id);
        }
    },

    methods: {
        onlyForCurrency ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || this.bid.amount.indexOf('.') != -1)) { // 46 is dot
            $event.preventDefault();
            }

            // restrict to 2 decimal places
            if(this.bid.amount!=null && this.bid.amount.indexOf(".")>-1 && (this.bid.amount.split('.')[1].length > 1)){
            $event.preventDefault();
            }
        },
        
        submitBid() {
            this.bid.ad_id = this.adId;
            this.$store.dispatch("ads/bid", this.bid);
        }
    }
}
</script>