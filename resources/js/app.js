import './bootstrap';

import Vue from 'vue';
import App from "./App";
import router from "./router";
import store from "./store";

import "@mdi/font/css/materialdesignicons.css";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {
                success: "#08c269"
            },
            dark: {
                success: "#08c269"
            }
        }
    }
};

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    vuetify: new Vuetify(opts),
    router,
    store,
    render: h => h(App),
}).$mount('#app');
