import Vue from "vue";
import Vuex from "vuex";
import {auth} from "./modules/auth";
import ads from "./modules/ads";
import categories from "./modules/categories";
import messages from "./modules/messages";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {auth, ads, categories, messages},
    state: {},
    mutations: {},
    getters: {},
    actions: {}
});
