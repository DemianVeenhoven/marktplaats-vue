<template>
    <div>
        <div>
            <Nav :isLoggedIn="isLoggedIn" />
        </div>

        <router-view/>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Nav from "./Nav.vue";

export default {
    created() {
        this.$store.dispatch("auth/setUser");
        this.$store.dispatch("ads/setAll");
        this.$store.dispatch("categories/setAll");
    },

    components: {
        Nav
    },

    computed: {
        ...mapGetters({
            user: "auth/getUser",
            isLoggedIn: "auth/getIsLoggedIn"
        })
        // user() {
        //     return this.$store.getters["auth/getUser"];
        // },

        // isLoggedIn() {
        //     return this.$store.getters["auth/getIsLoggedIn"];
        // }
    }
};
</script>
