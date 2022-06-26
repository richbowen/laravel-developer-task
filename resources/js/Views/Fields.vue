<template>
    <div class="d-flex flex-column fill-height">
        <v-card flat transparent>
            <v-toolbar dark>
                <v-app-bar-nav-icon @click.stop="$store.commit('app/DRAWER_TOGGLE')"></v-app-bar-nav-icon>
                <v-toolbar-title>Fields</v-toolbar-title>
                <v-spacer></v-spacer>
                <search-field @input="search = $event"></search-field>
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
                :items="fields"
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
                                v-for="(field, index) in props.items"
                                :key="index"
                            >
                                <v-card class="elevation-1" @click.stop="openShowDialog(field)">
                                    <v-card-title>{{ field.title }}</v-card-title>
                                    <v-card-actions>
                                        <v-chip>{{ field.type }}</v-chip>
                                        <v-spacer></v-spacer>
                                        <v-menu :nudge-width="100" offset-y>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn v-on="on" v-bind="attrs" icon>
                                                    <v-icon>mdi-dots-vertical</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-item @click="openEditDialog(field)">
                                                    <v-list-item-title>Edit</v-list-item-title>
                                                </v-list-item>
                                                <v-list-item @click="destroy(field.id)"> Delete </v-list-item>
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
                            <v-icon large>mdi-form-textbox</v-icon>
                        </div>
                        <div>No fields.</div>
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

            <v-dialog v-model="createDialog" max-width="600" persistent>
                <v-card>
                    <v-form ref="createFieldForm" @submit.prevent="store()">
                    <v-toolbar flat>
                        <v-btn icon @click="closeCreateDialog()">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>New Field</v-toolbar-title>
                    </v-toolbar>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Title"
                                        v-model="form.title"
                                        filled
                                        :error-messages="errors.first('title') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="field_types"
                                        label="Type"
                                        filled
                                        v-model="form.type"
                                        :error-messages="errors.first('type') || ''"
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
                        <v-toolbar-title>Edit {{ selected.title }}</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="editFieldForm" autocomplete="off">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Title"
                                        v-model="form.title"
                                        filled
                                        :error-messages="errors.first('title') || ''"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="field_types"
                                        label="Type"
                                        filled
                                        v-model="form.type"
                                        :error-messages="errors.first('type') || ''"
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
                        <v-toolbar-title>{{ selected.title }} Details</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon @click.stop="openEditDialog(selected)">
                            <v-icon>mdi-pencil-outline</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <span class="subtitle-2 font-weight-bold">Title</span>
                                <div>{{ selected.title }}</div>
                            </v-col>
                            <v-col cols="12">
                                <div class="subtitle-2 font-weight-bold">Type</div>
                                <div>{{ selected.type }}</div>
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
                title: null,
                type: null,
            },
            selected: {},
            editDialogTitle: null,
            errors: new Errors(),
        };
    },
    computed: {
        ...mapState({
            fields: (state) => state.fields.all,
            field_types: (state) => state.fields.types,
        }),
        exportUrl() {
            return "/api/fields/export";
        },
    },
    methods: {
        fetch() {
            this.loading = true;
            this.$store
                .dispatch("fields/fetch")
                .then((response) => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        store() {
            this.$store
                .dispatch("fields/store", this.form)
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
                .dispatch("fields/update", this.form)
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
            this.$store.dispatch("fields/destroy", id).then((response) => {
                this.$refs.confirmationMessage.open(response.data.message);
                this.fetch();
            });
        },
        closeCreateDialog() {
            this.createDialog = false;
            this.$refs.createFieldForm.reset();
            this.errors.clear();
        },
        openEditDialog(item) {
            this.form = Object.assign({
                id: item.id,
                title: item.title,
                type: item.type,
            });
            this.editDialogTitle = item.title;
            this.editDialog = true;
        },
        closeEditDialog() {
            this.editDialog = false;
            this.form = Object.assign({});
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
