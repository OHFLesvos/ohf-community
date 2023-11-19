import commonApi from '@/api/common'

export default {
    namespaced: true,

    state: {
        countryList: [],
        isCountryListLoaded: false,
    },

    mutations: {
        SET_COUNTRY_LIST(state, countryList) {
            state.countryList = countryList;
            state.isCountryListLoaded = true;
        },
    },

    actions: {
        async fetchCountryList({ commit, state }) {
            // Fetch country list only if it hasn't been loaded yet
            if (!state.isCountryListLoaded) {
                try {
                    let data = await commonApi.listCountries()
                    commit('SET_COUNTRY_LIST', Object.values(data));
                } catch (error) {
                    console.error('Error fetching country list:', error);
                }
            }
        },
    },

    getters: {
        getCountryList: state => state.countryList,
    },
};
