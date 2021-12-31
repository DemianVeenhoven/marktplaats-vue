<template>
    <div>
        <div>
            <b-toast v-model="showError" title="Error" variant="danger">{{getError}}</b-toast>
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="inputName">Username</label>
                <div>
                    <input id="inputName" v-model="auth.name" type="text"/>
                </div>
            </div>

            <!-- <div>
                <label for="inputPostalCode">Postal code</label>
                <div>
                    <input id="inputPostalCode" v-model="auth.postal_code" type="text"/>
                </div>
            </div> -->

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
                <b-button @click="register" variant="primary">Register</b-button>
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
                user_name: '',
                // postal_code: "",
                email: '',
                password: '',
                password_confirmation: '',
            },

            showError: false
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
        register() {
            this.$store.dispatch('auth/register', this.auth);
        },
    },
};
</script>

<style scoped>

</style>