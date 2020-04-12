/*
    Passport
 */
Vue.component('passport-clients', () => import(/* webpackChunkName:"passport-clients" */ './components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', () => import(/* webpackChunkName:"passport-authorized-clients" */ './components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', () => import(/* webpackChunkName:"passport-personal-access-tokens" */ './components/passport/PersonalAccessTokens.vue'));

// Vue.component( 'passport-clients', require('./components/passport/Clients.vue'));
// Vue.component( 'passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
// Vue.component( 'passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

