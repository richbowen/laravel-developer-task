import Vue from 'vue';
import store from './store';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "dashboard",
            component: () => import("./Views/Dashboard")
        },
        {
            path: "/subscribers",
            name: "subscribers",
            component: () => import("./Views/Subscribers")
        },
        {
            path: "/fields",
            name: "fields",
            component: () => import("./Views/Fields"),
            props: true
        },
    ]
});
