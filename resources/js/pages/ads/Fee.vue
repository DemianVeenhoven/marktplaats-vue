<template>
    <!-- ik wist geen betere plek om het te controleren of het verkocht was dan bij de delete button -->
    <div>
        <h1>Fee payment for {{getAd.title}}</h1>

        <div>
            <p>Your fee is: €{{getFee}}</p>

            <b-button @click="payFee()" variant="primary">Pay</b-button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            ad: {},
        }
    },

    computed: {
        adId() {
            return parseInt(this.$route.params.id);
        },

        getAd() {
            // TODO :: const
            let singleAd = this.$store.getters["ads/getSingleAd"](this.adId);

// TODO :: no need to put it in this.ad
            if(singleAd) {
                this.ad = singleAd;
                return singleAd;
            } else {
                return {}
            }
        },

        getFee() {
            if (this.ad.bids) {
                // TODO :: const
                let numb =  this.ad.bids[0].amount * 0.1
                return numb.toFixed(2);
                // TODO :: no need for the else
            } else {
                return null;
            }

        }
    },

    methods: {
        payFee() {
            this.$store.dispatch("ads/remove", this.ad.id);
        }
    }
}
</script>

<style scoped>

</style>
