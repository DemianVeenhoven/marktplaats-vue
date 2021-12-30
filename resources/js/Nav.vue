<template>
    <div>
        <b-nav tabs justified v-if="isLoggedIn" class="nav">
            <b-nav-item :to="{ name: 'ad.overview' }">All ads</b-nav-item>
            <b-nav-item :to="{ name: 'ad.create' }">Create a new ad</b-nav-item>
            <!-- My ads was My account but it only shows the ads so I renamed it in website, I didn't rename it in code because I use "account" a lot. Might change it in the future -->
            <b-nav-item :to="{ name: 'account' }">My ads</b-nav-item>
            <b-nav-item :to="{ name: 'allMessageChains' }">Messages</b-nav-item>
            <b-nav-item href="#" @click="logout" variant="outline-primary">Logout</b-nav-item>
        </b-nav>

        <b-nav tabs justified v-if="!isLoggedIn">
            <b-nav-item :to="{ name: 'ad.overview' }">All ads</b-nav-item>
            <b-nav-item :to="{ name: 'auth.login' }">Login</b-nav-item>
            <b-nav-item :to="{ name: 'auth.register' }">Register</b-nav-item>
        </b-nav>
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
/* i don't think this is a good solution */
    .nav {
        background: rgb(56, 56, 56);
    }
</style>