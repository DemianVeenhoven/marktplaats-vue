<template>
    <div>
        <div>
            <b-modal v-model="showError" title="Error" variant="danger" hide-footer>{{getError}}</b-modal>
        </div>

        <h1>Register</h1>

        <form @submit.prevent="submit">
            <div>
                <label for="inputName">Username</label>
                <div>
                    <input id="inputName" v-model="auth.name" type="text"/>
                </div>
            </div>

            <br>

            <div>
                <!-- <label for="inputPostalCode">Post code</label>
                <div>
                    <input id="inputPostalCode" v-model="auth.postalCode" type="text"/>
                </div> -->

                <div>
                    <multiselect
                        v-model="multiselectArray" 
                        :options="postalCodes" 
                        :multiple="false" 
                        :close-on-select="true" 
                        :clear-on-select="false" 
                        placeholder="Postal codes" 
                        label="postcode" 
                        track-by="id" 
                        :preselect-first="false" 
                        :searchable="true"
                    ></multiselect>
                </div>
            </div>

            <br>

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
import Multiselect from 'vue-multiselect';

export default {
    components: {
        Multiselect
    },

    data() {
        return {
            auth: {
                name: '',
                postalCode: "",
                email: '',
                password: '',
                password_confirmation: '',
            },

            multiselectArray: [],

            showError: false
        };
    },

    computed: {
        ...mapGetters({
            postalCodes: "postalCodes/getAll"
        }),

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