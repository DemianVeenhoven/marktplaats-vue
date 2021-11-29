<template>
    <div>
        <router-link :to="{ name: 'ad.overview' }">
            All ads
        </router-link>

        <br>

        <div v-if="isLoggedIn">
            <router-link :to="{ name: 'ad.create' }">
                Create a new ad
            </router-link>
            
            <br>

            <router-link :to="{ name: 'account' }">
                My account
            </router-link>

            <br>

            <a href="#" @click="logout">
                Logout
            </a>
        </div>

        <div v-if="!isLoggedIn">
            <router-link :to="{ name: 'auth.login' }">
                Login
            </router-link>

            <br>

            <router-link :to="{ name: 'auth.register' }">
                Register
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "isLoggedIn"
    ],
    
    methods: {
        logout() {
            this.$store.dispatch("auth/logout");
        }
    }
}
</script>

<style scoped>

</style>