<template>
    <v-navigation-drawer fixed overflow app v-model="inputValue" hide-overlay class="overflow-y-auto">
        <template v-slot:prepend>
            <v-list dense nav>
                <v-list-item>
                    <v-list-item-content class="align-center justify-center">
                        <v-img
                            class="mb-2"
                            max-width="200"
                            src="/images/mailerlite.png"
                            lazy-src="/images/mailerlite.png"
                            contain
                        ></v-img>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item to="/">
                    <v-list-item-action>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item>

                <v-list-item to="/subscribers">
                    <v-list-item-action>
                        <v-icon>mdi-account-group</v-icon>
                    </v-list-item-action>
                    <v-list-item-title>Subscribers</v-list-item-title>
                </v-list-item>

                <v-list-item to="/fields">
                    <v-list-item-action>
                        <v-icon>mdi-form-textbox</v-icon>
                    </v-list-item-action>
                    <v-list-item-title>Fields</v-list-item-title>
                </v-list-item>
            </v-list>
        </template>
        <template v-slot:append v-if="githubUser">
            <v-divider></v-divider>
            <v-list dense nav>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img
                            :src="githubUser.avatar_url"
                            :lazy-src="githubUser.avatar_url"
                        ></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{ githubUser.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ githubUser.login }}</v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn icon :href="githubUser.html_url">
                            <v-icon>mdi-link</v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
            </v-list>
        </template>
    </v-navigation-drawer>
</template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
    mounted() {
        this.$store.dispatch("user/fetch");
    },
    computed: {
        ...mapState({
            appDrawer: (state) => state.app.appDrawer,
            githubUser: (state) => state.user.user
        }),
        inputValue: {
            get() {
                return this.appDrawer;
            },

            set(val) {
                this.drawer(val);
            },
        },
    },
    methods: {
        ...mapMutations("app", {
            drawer: "DRAWER",
        }),
    },
};
</script>
