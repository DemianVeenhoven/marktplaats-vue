<template>
    <div>
        <h1>Upgrade ad to premium</h1>

        <p>For €10.00 you can upgrade your ad to premium which means it will be shown at the top!</p>

        <b-button @click="upgradeAd()" variant="primary">Upgrade</b-button>

        <b-button
            :to="{ name: 'ad.edit', params: {id: adId}}" 
            variant="primary"
        >Back to ad</b-button>
    </div>
</template>

<script>
export default {
    data() {
        return  {
            ad: {},
        }
    },

    computed: {
        adId() {
            return parseInt(this.$route.params.id);
        }
    },

    methods:{
        upgradeAd() {
            this.$store.dispatch("ads/upgrade", this.adId);
        }
    }
}
</script>

<style scoped>

</style>