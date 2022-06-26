<template>
    <v-snackbar
        v-model="snackbar"
        :bottom="bottom"
        :left="left"
        :multi-line="mode === 'multi-line'"
        :right="right"
        :timeout="timeout"
        :top="top"
        :vertical="mode === 'vertical'"
        :color="color"
        :dark="dark"
    >
        {{ text }}
        <template v-slot:action="{ attrs }">
            <v-btn text v-bind="attrs" @click="snackbar = false">Close</v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    props: {
        timeout: {
            type: Number,
            required: false,
            default: 6000
        },
        color: {
            type: String,
            required: false,
            default: 'default'
        },
        dark: {
            type: Boolean,
            required: false,
            default: false
        },
        mode: {
            type: String,
            required: false,
        },
        top: {
            type: Boolean,
            required: false
        },
        bottom: {
            type: Boolean,
            required: false
        },
        left: {
            type: Boolean,
            required: false
        },
        right: {
            type: Boolean,
            required: false
        },
    },
    data() {
        return {
            text: '',
            snackbar: false,
        }
    },

    methods: {
        open(msg) {
            this.text = msg;
            this.snackbar = true;
        }
    }
}
</script>
