import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    strict: true,
    state: {
        previousRoute: {},
        settings: {}
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
        }
    }
});
