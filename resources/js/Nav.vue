<template>
    <div>
        <b-nav tabs justified v-if="isLoggedIn" class="nav">
        <!-- TODO :: all ads stays active, even when on another page -->
            <b-nav-item :to="{ name: 'ad.overview' }">All ads</b-nav-item>
            <b-nav-item :to="{ name: 'ad.create' }">Create a new ad</b-nav-item>
            <b-nav-item :to="{ name: 'account' }">My account</b-nav-item>
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
