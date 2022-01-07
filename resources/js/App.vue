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
        this.$store.dispatch("postalCodes/setAll");
    },

    components: {
        Nav
    },

    computed: {
        ...mapGetters({
            user: "auth/getUser",
            isLoggedIn: "auth/getIsLoggedIn"
        })
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
