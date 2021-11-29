<template>
    <div>
        <div v-if="errorMessage" class="error">
            <br>

            <p>{{errorMessage}}</p>

            <br>
        </div>

        <form @submit.prevent="submit">
            <div >
                <label for="inputEmail">Email</label>
                <div>
                    <input id="inputEmail" v-model="auth.email" type="text"/>
                </div>
            </div>

            <button type="button" @click="sendEmail">
                Send email
            </button>
        </form>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            auth: {
                email: '',
                password: '',
                password_confirmation: '',
            },
        };
    },

    computed: {
        ...mapGetters({
            errorMessage: "auth/getError"
        })
    },

    methods: {
        sendEmail() {
            this.$store.dispatch('auth/sendEmail', this.auth);
        },
    },
}
</script>

<style scoped>
    .error {
        background-color: red; 
    }
</style>