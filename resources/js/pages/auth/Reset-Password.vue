<template>
    <div>
        <div>
            <b-modal v-model="modalShow" title="Error" hide-footer>{{getError}}</b-modal>
        </div>

        <form @submit.prevent="submit">
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

            modalShow: false
        };
    },

    computed: {
        getError() {
            const errorMessage = this.$store.getters["auth/getError"];

            if (errorMessage) {
                this.modalShow = true
            }
            
            return errorMessage
        }
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