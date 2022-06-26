<template>
    <v-app id="#app">
        <app-drawer></app-drawer>
        <v-main>
            <router-view class="d-flex flex-column fill-height"></router-view>
        </v-main>
    </v-app>
</template>

<script>
import store from './store';

export default {
    name: "app",
    beforeCreate() {
        store.dispatch("subscribers/fetch");
        store.dispatch("fields/fetch");
    }
};
</script>
