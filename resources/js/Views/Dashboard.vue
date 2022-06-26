<template>
    <div class="d-flex flex-column fill-height">
        <v-card flat transparent>
            <v-toolbar dark>
                <v-app-bar-nav-icon @click.stop="$store.commit('app/DRAWER_TOGGLE')"></v-app-bar-nav-icon>
                <v-toolbar-title>Dashboard</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>

            <v-container fluid>
                <v-row>
                    <v-col cols="12" md="4">
                        <v-card class="elevation-1">
                            <v-list-item two-line>
                                <v-list-item-avatar tile size="80">
                                    <v-icon large color="success">mdi-account-group</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <div class="body-2 grey--text text--darken-2">Total Subscribers</div>
                                    <v-list-item-title class="display-1">
                                        {{ totalSubscribers }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card class="elevation-1">
                            <v-list-item two-line>
                                <v-list-item-avatar tile size="80">
                                    <v-icon large color="success">mdi-form-textbox</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <div class="body-2 grey--text text--darken-2">Total Fields</div>
                                    <v-list-item-title class="display-1">
                                        {{ totalFields }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6">
                        <v-card class="elevation-1">
                            <v-toolbar flat transparent dense>
                                <v-toolbar-title>Subscribers</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn icon to="/subscribers" exact>
                                    <v-icon>mdi-arrow-right</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-spacer></v-spacer>
                            <v-data-table
                                :headers="subscriberHeaders"
                                :items="subscribers"
                                :items-per-page="5"
                                hide-default-footer
                                ref="subscribersDataTable"
                            >
                                <template v-slot:no-data>
                                    <div class="d-flex flex-column align-center justify-center my-5">
                                        <div class="text-h4 py-4 grey--text text--darken-3">
                                            <v-icon large>mdi-account-multiple-plus</v-icon>
                                        </div>
                                        <div>No matching records found.</div>
                                    </div>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-card class="elevation-1">
                            <v-toolbar flat transparent dense>
                                <v-toolbar-title>Fields</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn icon to="/fields" exact>
                                    <v-icon>mdi-arrow-right</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-spacer></v-spacer>
                            <v-data-table
                                :headers="fieldHeaders"
                                :items="fields"
                                :items-per-page="5"
                                hide-default-footer
                                ref="fieldsDataTable"
                            >
                                <template v-slot:no-data>
                                    <div class="d-flex flex-column align-center justify-center my-5">
                                        <div class="text-h4 py-4 grey--text text--darken-3">
                                            <v-icon large>mdi-form-textbox</v-icon>
                                        </div>
                                        <div>No matching records found.</div>
                                    </div>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    data: () => ({
        subscriberHeaders: [
            { text: "Name", value: "name" },
            { text: "Email Address", value: "email" },
            { text: "State", value: "state" },
        ],
        fieldHeaders: [
            { text: "Title", value: "title" },
            { text: "Type", value: "type" },
        ],
    }),
    computed: {
        ...mapState({
            subscribers: (state) => state.subscribers.all,
            fields: (state) => state.fields.all,
        }),
        totalSubscribers() {
            return this.subscribers?.length ?? 0;
        },
        totalFields() {
            return this.fields?.length ?? 0;
        },
    },
};
</script>
