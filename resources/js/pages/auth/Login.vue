<template>
    <div>
        <div>
            <b-modal v-model="showError" title="Error" variant="danger" hide-footer>{{getError}}</b-modal>
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="staticEmail">Email</label>
                <div>
                    <input id="inputEmail" v-model="auth.email" type="text" />
                </div>
            </div>

            <div>
                <label for="inputPassword">Password</label>
                <div>
                    <input
                        id="inputPassword"
                        v-model="auth.password"
                        type="password"
                    />
                </div>
            </div>

            <br>

            <div>
                <b-button @click="login" variant="primary">Login</b-button>
                <b-button :to="{ name: 'auth.sendLink' }" variant="primary">Change Password</b-button>
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
                email: "",
                password: ""
            },

            showError: false
        };
    },

    computed: {
        getError() {
            const errorMessage = this.$store.getters["auth/getError"];

            if (errorMessage) {
                this.showError = true;
            }
            
            return errorMessage
        }
    },

    methods: {
        login() {
            this.$store.dispatch("auth/login", this.auth);
        }
    }
};
</script>

<style scoped>
    .error {
        background-color: red; 
    }
</style>