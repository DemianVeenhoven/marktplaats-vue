import Vue from "vue";
import VueRouter from "vue-router";

import AdOverview from '../pages/ads/Overview.vue'
import AdCreate from '../pages/ads/Create.vue'
import AdEdit from '../pages/ads/Edit.vue'
import AdBid from '../pages/ads/Bid.vue'
import AdPremium from '../pages/ads/Premium.vue'
import AdFee from '../pages/ads/Fee.vue'
import AllMessageChains from '../pages/messages/MyMessageChains.vue'
import MessageChain from '../pages/messages/MessageChain.vue'
import Login from '../pages/auth/Login.vue'
import Register from '../pages/auth/Register.vue'
import SendResetLink from '../pages/auth/Reset-link.vue'
import ResetPassword from '../pages/auth/Reset-Password.vue'
import Account from "../pages/Account.vue"

Vue.use(VueRouter);

const router = new VueRouter({
    mode:"history",
    routes: [
        {
            path: "/",
            component: AdOverview,
            name: "ad.overview"
        },
        {
            path: "/ads/create",
            component: AdCreate,
            name: "ad.create"
        },
        {
            path: "/ads/:id/edit",
            component: AdEdit,
            name: "ad.edit"
        },
        {
            path: "/ads/:id/bid",
            component: AdBid,
            name: "ad.bid"
        },
        {
            path: "/ads/:id/premium",
            component: AdPremium,
            name: "ad.premium"
        },
        {
            path: "/ads/:id/fee",
            component: AdFee,
            name: "ad.fee"
        },
        {
            path: "/message_chain",
            component: AllMessageChains,
            name: "allMessageChains"
        },
        {
            path: "/message_chain/:id",
            component: MessageChain,
            name: "messageChain"
        },
        {
            path: '/login',
            component: Login,
            name: 'auth.login',
            meta: {
                shouldBeLoggedOut: true,
            },
        },
        {
            path: '/register',
            component: Register,
            name: 'auth.register',
            meta: {
                shouldBeLoggedOut: true,
            },
        },
        {
            path: '/account',
            component: Account,
            name: 'account',
            meta: {
                shouldBeLoggedIn: true,
            },
        },
        {
            path: '/api/reset-password',
            component: SendResetLink,
            name: 'auth.sendLink',
            meta: {
                shouldBeLoggedOut: true,
            },
           
        },
        {
            path: '/api/reset-password/:token',
            component: ResetPassword,
            name: 'auth.reset',
            meta: {
                shouldBeLoggedOut: true,
            },
        },
        {
            path: '*',
            component: AdOverview
        },
    ]
});

// /reset-password/{token}

// router.beforeEach(({meta}, from, next) => {
//     const isLoggedIn = store.getters['auth/getIsLoggedIn'];
//     if (meta.shouldBeLoggedIn && !isLoggedIn) next({name: 'auth.login'});
//     if (meta.shouldBeLoggedOut && isLoggedIn) next({name: 'account'});
//     next();
// });

export default router;
