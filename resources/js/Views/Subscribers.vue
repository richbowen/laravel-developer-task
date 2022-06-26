<template>
    <div class="d-flex flex-column fill-height">
        <v-card flat transparent>
            <v-toolbar dark>
                <v-app-bar-nav-icon @click.stop="$store.commit('app/DRAWER_TOGGLE')"></v-app-bar-nav-icon>
                <v-toolbar-title>Subscribers</v-toolbar-title>
                <v-spacer></v-spacer>
                <search-field @input="search = $event" dark></search-field>
                <v-btn icon @click="fetch()" :loading="loading">
                    <v-icon>mdi-refresh</v-icon>
                </v-btn>
                <v-btn icon :href="exportUrl">
                    <v-icon>mdi-download</v-icon>
                </v-btn>
                <v-btn @click.stop="createDialog = !createDialog" icon>
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>

            <v-data-iterator
                :items="subscribers"
                :search="search"
                hide-default-footer
                disable-pagination
                :loading="loading"
            >
                <template v-slot:default="props">
                    <v-container fluid>
                        <v-row>
                            <v-col
                                cols="12"
                                xs="12"
                                sm="6"
                                md="4"
                                lg="3"
                                v-for="(subscriber, index) in props.items"
                                :key="index"
                            >
                                <v-card class="elevation-1" @click.stop="openShowDialog(subscriber)">
                                    <v-card-title>{{ subscriber.name }}</v-card-title>
                                    <v-card-subtitle>{{ subscriber.email }}</v-card-subtitle>
                                    <v-card-actions>
                                        <v-chip>{{ subscriber.state }}</v-chip>
                                        <v-spacer></v-spacer>
                                        <v-menu :nudge-width="100" offset-y>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn v-on="on" v-bind="attrs" icon>
                                                    <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-item @click="openEditDialog(subscriber)">
                                                    <v-list-item-title>Edit</v-list-item-title>
                                                </v-list-item>
                                                <v-list-item @click="destroy(subscriber.id)">
                                                    Delete
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </template>
                <template v-slot:no-data>
                    <div class="d-flex flex-column align-center justify-center my-5">
                        <div class="text-h4 py-4 grey--text text--darken-3">
                            <v-icon large>mdi-account-multiple-plus</v-icon>
                        </div>
                        <div>No subscribers.</div>
                    </div>
                </template>
                <template v-slot:no-results>
                    <div class="d-flex flex-column align-center justify-center my-5">
                        <div class="text-h4 py-4 grey--text text--darken-3">
                            <v-icon large>mdi-account-multiple-plus</v-icon>
                        </div>
                        <div>No matching records found.</div>
                    </div>
                </template>
            </v-data-iterator>

            <v-dialog v-model="createDialog" scrollable max-width="600" persistent>
                <v-card>
                    <v-form ref="createSubscriberForm" @submit.prevent="store()">
                        <v-toolbar flat>
                            <v-btn icon @click="closeCreateDialog()">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                            <v-toolbar-title>New Subscriber</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Subscriber Name"
                                        v-model="form.name"
                                        filled
                                        :error-messages="errors.first('name') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Email Address"
                                        v-model="form.email"
                                        filled
                                        :error-messages="errors.first('email') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="subscriber_states"
                                        label="State"
                                        filled
                                        v-model="form.state"
                                        :error-messages="errors.first('state') || ''"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" text @click="closeCreateDialog()">Cancel</v-btn>
                            <v-btn type="submit" color="primary">Save</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-model="editDialog" scrollable max-width="600" persistent>
                <v-card>
                    <v-toolbar flat>
                        <v-btn icon @click="closeEditDialog()">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Edit {{ editDialogTitle }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="editSubscriberForm" autocomplete="off">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Subscriber Name"
                                        v-model="form.name"
                                        filled
                                        :error-messages="errors.first('name') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Email Address"
                                        v-model="form.email"
                                        filled
                                        :error-messages="errors.first('email') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="subscriber_states"
                                        label="State"
                                        filled
                                        v-model="form.state"
                                        :error-messages="errors.first('state') || ''"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="fields"
                                        label="Fields"
                                        item-text="title"
                                        item-value="id"
                                        filled
                                        multiple
                                        chips
                                        deletable-chips
                                        v-model="form.field_ids"
                                        :error-messages="errors.first('field_ids') || ''"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click="closeEditDialog()">Cancel</v-btn>
                        <v-btn color="primary" @click="update()">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="showDialog" scrollable max-width="800" persistent>
                <v-card>
                    <v-toolbar flat>
                        <v-btn icon @click="closeShowDialog()"><v-icon>mdi-close</v-icon></v-btn>
                        <v-toolbar-title>{{ selected.name }} Details</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click.stop="openEditDialog(selected)">
                            <v-icon>mdi-pencil-outline</v-icon></v-btn
                        >
                    </v-toolbar>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <span class="subtitle-2 font-weight-bold">Subscriber</span>
                                <div>{{ selected.name }}</div>
                            </v-col>
                            <v-col cols="12">
                                <div class="subtitle-2 font-weight-bold">Email Address</div>
                                <div>{{ selected.email }}</div>
                            </v-col>
                            <v-col cols="12">
                                <div class="subtitle-2 font-weight-bold">State</div>
                                <div>{{ selected.state }}</div>
                            </v-col>
                            <v-col cols="12">
                                <div class="subtitle-2 font-weight-bold mb-2">Fields</div>
                                <v-row v-if="selected.fields && selected.fields.length > 0" dense>
                                    <v-col
                                        cols="12"
                                        md="4"
                                        v-for="(field, index) in selected.fields"
                                        :key="index"
                                    >
                                        <v-card outlined tile>
                                            <v-card-title class="body-1">{{ field.title }}</v-card-title>
                                            <v-card-subtitle class="body-2">{{ field.type }}</v-card-subtitle>
                                        </v-card>
                                    </v-col>
                                </v-row>
                                <div v-else>No fields.</div>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click.native="closeShowDialog()">Close</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card>
        <confirmation-message ref="confirmationMessage" mode="multi-line"></confirmation-message>
    </div>
</template>

<script>
import { mapState } from "vuex";
import { Errors } from "form-backend-validation";

export default {
    data() {
        return {
            search: "",
            loading: null,
            createDialog: false,
            editDialog: false,
            showDialog: false,
            form: {
                name: null,
                email: null,
                state: null,
                field_ids: null,
            },
            selected: {},
            editDialogTitle: null,
            errors: new Errors(),
        };
    },
    computed: {
        ...mapState({
            subscribers: (state) => state.subscribers.all,
            fields: (state) => state.fields.all,
            subscriber_states: (state) => state.subscribers.states,
        }),
        exportUrl() {
            return "/api/subscribers/export";
        },
    },
    methods: {
        async fetch() {
            this.loading = true;
            await this.$store
                .dispatch("subscribers/fetch")
                .then((response) => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        store() {
            this.$store
                .dispatch("subscribers/store", this.form)
                .then((response) => {
                    this.$refs.confirmationMessage.open(response.data.message);
                    this.closeCreateDialog();
                    this.fetch();
                })
                .catch((error) => {
                    this.errors = new Errors(error.response.data.errors);
                });
        },
        update() {
            this.$store
                .dispatch("subscribers/update", this.form)
                .then((response) => {
                    this.$refs.confirmationMessage.open(response.data.message);
                    this.closeEditDialog();
                    this.fetch();
                })
                .catch((error) => {
                    this.errors = new Errors(error.response.data.errors);
                });
        },
        destroy(id) {
            const answer = confirm(`Are you sure you want to delete this subscriber?`);
            if (answer) {
                this.$store.dispatch("subscribers/destroy", id).then((response) => {
                    this.$refs.confirmationMessage.open(response.data.message);
                    this.fetch();
                });
            }
        },
        closeCreateDialog() {
            this.createDialog = false;
            this.$refs.createSubscriberForm.reset();
            this.errors.clear();
        },
        openEditDialog(item) {
            this.form = Object.assign({
                id: item.id,
                name: item.name,
                email: item.email,
                state: item.state,
                field_ids: item.fields.map((field) => field.id),
            });
            this.editDialogTitle = item.name;
            this.editDialog = true;
        },
        closeEditDialog() {
            this.editDialog = false;
            this.form = Object.assign({});
            this.editDialogTitle = null;
            this.errors.clear();
            this.fetch();
        },
        openShowDialog(item) {
            this.showDialog = true;
            this.selected = Object.assign({}, item);
        },
        closeShowDialog() {
            this.showDialog = false;
            this.selected = Object.assign({});
        },
    },
};
</script>
