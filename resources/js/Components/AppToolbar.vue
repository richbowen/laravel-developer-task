<template>
    <v-toolbar color="success" dark>
        <v-app-bar-nav-icon
            @click.stop="$store.commit('app/DRAWER_TOGGLE')"
                ></v-app-bar-nav-icon>
        <v-toolbar-title>{{ title }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <search-field
            v-if="search"
            @input="search"
            :background-color="`success darken-1`"
            dark
        ></search-field>
        <v-btn icon @click="reload()" :loading="loading">
            <v-icon>mdi-refresh</v-icon>
        </v-btn>
        <v-btn icon :href="exportUrl">
            <v-icon>mdi-download</v-icon>
        </v-btn>
        <v-btn icon @click.stop="create()">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
    </v-toolbar>
</template>

<script>
export default {
    name: "AppToobar",
    props: {
        title: {
            type: String,
            required: true,
        },
        exportUrl: {
            type: Boolean,
            required: false,
            default: false
        },
        create: {
            type: Boolean,
            required: false,
            default: false
        },
        search: {
            type: String,
            required: false
        }
    },
    methods: {
        reload() {
            this.$emit("reload");
        },
        create() {
            this.$emit("create");
        }
    }
};
</script>
