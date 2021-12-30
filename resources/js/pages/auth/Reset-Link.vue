<template>
    <div>
        <div>
            <b-toast v-model="showError" title="Error" variant="danger">{{getError}}</b-toast>
        </div>

        <div v-if="emailSend">
            <p>An email with a reset link has been send to {{auth.email}}</p>
        </div>

        <form @submit.prevent="submit" v-else>
            <div >
                <label for="inputEmail">Email</label>
                <div>
                    <input id="inputEmail" v-model="auth.email" type="text"/>
                </div>
            </div>

            <br>

            <div>
                <b-button @click="sendEmail" variant="primary">Send email</b-button>
            </div>
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

            showError: false,

            emailSend: false
        };
    },

    computed: {
        getError() {
            const errorMessage = this.$store.getters["auth/getError"];

            if (errorMessage) {
                this.showError = true
            }
            
            return errorMessage
        }
    },

    methods: {
        sendEmail() {
            this.$store.dispatch('auth/sendEmail', this.auth);

            this.emailSend = true;
        },
    },
}
</script>

<style scoped>

</style>