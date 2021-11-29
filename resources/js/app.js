import Vue from "vue";

//Main pages
import App from "./App.vue";

//Import store
import store from "./store";

//Import router
import router from "./router";

import '../css/app.scss'

// Vue.config.debug = true; 
// Vue.config.devtools = true;

new Vue({
    el: "#app",
    store,
    router,
    render: (h) => h(App),
});
