<template>
    <v-responsive :max-width="maxWidth">
        <v-text-field
            @input="$emit('input', $event)"
            prepend-inner-icon="mdi-magnify"
            :label="label"
            :dark="dark"
            solo
            flat
            hide-details
            dense
            :background-color="backgroundColor"
            class="mr-2"
            autocomplete="off"
        ></v-text-field>
    </v-responsive>
</template>

<script>
export default {
    name: "SearchField",
    props: {
        label: {
            type: String,
            required: false,
            default: "Search",
        },
        backgroundColor: {
            type: String,
            required: false,
            default: "grey darken-3",
        },
        maxWidth: {
            type: String,
            required: false,
            default: "500",
        },
        dark: {
            type: Boolean,
            required: false,
            dafault: true,
        },
    },
};
</script>
