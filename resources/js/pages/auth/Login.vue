<template>
    <div>
        <div v-if="errorMessage" class="error">
            <br>

            <p>{{errorMessage}}</p>

            <br>
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

            <button type="button" @click="login">
                Inloggen
            </button>

            <br>

            <router-link :to="{ name: 'auth.reset' }" tag="button">Change Password</router-link>
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
            }
        };
    },

    computed: {
        ...mapGetters({
            errorMessage: "auth/getError"
        })
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