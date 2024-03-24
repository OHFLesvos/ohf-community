import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import countryModule from '@/store/countries'

export default new Vuex.Store({
    modules: {
        country: countryModule,
    },
    strict: true,
    state: {
        previousRoute: {},
        settings: {},
        title: undefined,
        authenticatedUser: undefined,
    },
    getters: {
        getPreviousRoute: state => (name, defaultValue) => {
            return state.previousRoute[name]
                ? state.previousRoute[name]
                : defaultValue;
        }
    },
    mutations: {
        SET_PREVIOUS_ROUTE(state, payload) {
            state.previousRoute[payload.name] = payload.value;
        },
        SET_SETTINGS(state, payload) {
            state.settings = payload;
        },
        SET_TITLE(state, payload) {
            state.title = payload
        },
        SET_AUTHENTICATED_USER(state, payload) {
            state.authenticatedUser = payload
        },
    }
});
