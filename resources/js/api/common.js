import { api, route } from "@/api/baseApi";
export default {
    /**
     * Gets a list of official languages (language code => localized name)
     */
    async listLanguages(locale = null) {
        const params = locale
            ? {
                  locale: locale,
              }
            : {};
        const url = route("api.languages", params);
        return await api.get(url);
    },
    /**
     * Gets a list of official country names (country code => localized name)
     */
    async listCountries(locale = null) {
        const params = locale
            ? {
                  locale: locale,
              }
            : {};
        const url = route("api.countries", params);
        return await api.get(url);
    },
    /**
     * Gets a list of currencies (country code => name)
     */
    async listCurrencies() {
        const url = route("api.currencies");
        return await api.get(url);
    },
};
