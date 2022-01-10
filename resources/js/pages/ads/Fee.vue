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
    computed: {
        adId() {
            return parseInt(this.$route.params.id);
        },

        getAd() {
            const singleAd = this.$store.getters["ads/getSingleAd"](this.adId);

            if(singleAd) {
                return singleAd;
            } 

            return {};
        },

        getFee() {
            if (this.getAd.bids) {
                const numb =  this.getAd.bids[0].amount * 0.1
                return numb.toFixed(2);
            } 

            return null;
        }
    },

    methods: {
        payFee() {
            this.$store.dispatch("ads/remove", this.getAd.id);
        }
    }
}
</script>

<style scoped>

</style>