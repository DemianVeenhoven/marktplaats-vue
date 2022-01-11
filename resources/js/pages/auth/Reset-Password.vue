<template>
    <div>
        <div>
            <b-modal v-model="showError" title="Error" variant="danger" hide-footer>{{getError}}</b-modal>
        </div>

        <form @submit.prevent="submit">
            <div >
                <label for="inputEmail">Email</label>
                <div>
                    <input id="inputEmail" v-model="auth.email" type="text"/>
                </div>
            </div>

            <div>
                <label for="inputPassword">Password (min. 8 characters)</label>
                <div>
                    <input id="inputPassword" v-model="auth.password" type="password"/>
                </div>
            </div>
            <div>
                <label for="inputPasswordConfirmation">Password Confirmation</label>
                <div>
                    <input id="inputPasswordConfirmation" v-model="auth.password_confirmation" type="password"/>
                </div>
            </div>

            <br>

            <div>
                <b-button @click="reset" variant="primary">Reset password</b-button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    created() {
        this.setToken();
    },

    data() {
        return {
            auth: {
                token: '',
                email: '',
                password: '',
                password_confirmation: '',
            },

            showError: false,

            errorMessage: null
        };
    },

    computed: {
        getError() {
            const errorMessage = this.$store.getters["auth/getError"];

            if (errorMessage) {
                this.showError = true;
                this.errorMessage = errorMessage;
                this.$store.commit("auth/CLEAR_ERROR");
                return errorMessage
            }

            return null;
        }
    },

    methods: {
        setToken() {
            this.auth.token = this.$route.params.token;
        },

        reset() {
            this.errorMessage = null;
            this.showError = false;
            
            this.$store.dispatch('auth/resetPassword', this.auth);
        },
    },
};
</script>

<style scoped>

</style>