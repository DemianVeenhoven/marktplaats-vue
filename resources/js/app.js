import Vue from "vue";

//Main pages
import App from "./App.vue";

//Import store
import store from "./store";

//Import router
import router from "./router";

import '../css/app.scss'

import { BootstrapVue} from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import axios from "axios";

Vue.use(BootstrapVue)

// Vue.config.debug = true; 
// Vue.config.devtools = true;

axios.interceptors.response.use(
    function (response) {
        // Any status code that lie within the range of 2xx cause this function to trigger
        // do nothing, return response
        
        store.commit("auth/CLEAR_ERROR");
        return response;
    },
    function (error) {
        if (!error.response) {
            store.commit("auth/CLEAR_ERROR");
            return Promise.reject(error);
        }
        // Any status codes that falls outside the range of 2xx cause this function to trigger
        // Do something with response error

        const errors = error.response.data.errors || [];
        console.log(error.response.data.errors);
        store.commit("auth/SET_ERROR", errors[Object.keys(errors)[0]][0]);
        // store.commit("auth/SET_ERROR", errors[0][0]);
        throw error;
        // store.commit('setStatus', 'error');
    }
);

new Vue({
    el: "#app",
    store,
    router,
    render: (h) => h(App),
});