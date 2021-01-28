require('./bootstrap');

import Vue from 'vue';
import App from './App.vue'
import store from './store';
import router from './router';
import Vuelidate from 'vuelidate';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

Vue.router = router;
Vue.axios = window.axios;

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(Vuelidate);

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
