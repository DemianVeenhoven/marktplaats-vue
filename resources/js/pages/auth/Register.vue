<template>
    <div>
        <div v-if="errorMessage" class="error">
            <br>

            <p>{{errorMessage}}</p>

            <br>
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="inputName">Username</label>
                <div>
                    <input id="inputName" v-model="auth.name" type="text"/>
                </div>
            </div>

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

            <button type="button" @click="register">
                Register
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
                user_name: '',
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
        register() {
            this.$store.dispatch('auth/register', this.auth);
        },
    },
};
</script>

<style scoped>
    .error {
        background-color: red; 
    }
</style>