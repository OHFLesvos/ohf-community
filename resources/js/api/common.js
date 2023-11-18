import { api, route } from '@/api/baseApi'
export default {
    /**
     * Gets a list of official languages (language code => localized name)
     */
    async listLocalizedLanguages (locale = null) {
        const params = locale ? {
            locale: locale
        } : {}
        const url = route('api.localizedLanguages', params)
        return await api.get(url)
    },
    /**
     * Gets a list of official country names (country code => name)
     */
    async listCountries () {
        const url = route('api.countries')
        return await api.get(url)
    },
    /**
     * Gets a list of official country names (country code => localized name)
     */
    async listLocalizedCountries (locale = null) {
        const params = locale ? {
            locale: locale
        } : {}
        const url = route('api.localizedCountries', params)
        return await api.get(url)
    }
}
