import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const app = {
    namespaced: true,

    state: () => ({
        appDrawer: null,
    }),
    mutations: {
        DRAWER(state, payload) {
            state.appDrawer = payload;
        },

        DRAWER_TOGGLE(state) {
            state.appDrawer = !state.appDrawer;
        },
    },
    actions: {
        toggleDrawer({ commit }) {
            commit("DRAWER_TOGGLE");
        },
    },
};

const subscribers = {
    namespaced: true,

    state: () => ({
        all: [],
        states: [],
    }),
    mutations: {
        SET_SUBSCRIBERS(state, subscribers) {
            state.all = subscribers;
        },
        SET_SUBSCRIBER_STATES(state, states) {
            state.states = states;
        },
    },
    actions: {
        fetch({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/subscribers")
                    .then((response) => {
                        commit("SET_SUBSCRIBERS", response.data);
                        dispatch('fetchSubscriberStates');
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        store({ dispatch }, { name, email, state, field_ids }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/subscribers`, {
                        name,
                        email,
                        state,
                        field_ids
                    })
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        update({ dispatch }, { id, name, email, state, field_ids }) {
            return new Promise((resolve, reject) => {
                axios
                    .patch(`/api/subscribers/${id}`, {
                        name,
                        email,
                        state,
                        field_ids
                    })
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        destroy({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/subscribers/${id}`)
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        fetchSubscriberStates({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/subscriber_states")
                    .then((response) => {
                        commit("SET_SUBSCRIBER_STATES", response.data.subscriber_states);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        }
    },
};

const fields = {
    namespaced: true,

    state: () => ({
        all: [],
        types: [],
    }),
    mutations: {
        SET_FIELDS(state, fields) {
            state.all = fields;
        },
        SET_FIELD_TYPES(state, types) {
            state.types = types;
        },
    },
    actions: {
        fetch({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/fields")
                    .then((response) => {
                        commit("SET_FIELDS", response.data);
                        dispatch('fetchFieldTypes');
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        store({ dispatch }, { title, type }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/fields`, {
                        title,
                        type,
                    })
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        update({ dispatch }, { id, title, type }) {
            return new Promise((resolve, reject) => {
                axios
                    .patch(`/api/fields/${id}`, {
                        title,
                        type,
                    })
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        destroy({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/fields/${id}`)
                    .then((response) => {
                        dispatch("fetch");
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        fetchFieldTypes({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/field_types")
                    .then((response) => {
                        commit("SET_FIELD_TYPES", response.data.field_types);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        }
    },
};
const user = {
    namespaced: true,
    state: () => ({
        user: null,
    }),
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
    },
    actions: {
        fetch({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("https://api.github.com/users/richbowen")
                    .then((response) => {
                        const user = response.data;
                        commit("SET_USER", user);
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
    },
};

export default new Vuex.Store({
    modules: {
        app,
        subscribers,
        fields,
        user
    },
});
