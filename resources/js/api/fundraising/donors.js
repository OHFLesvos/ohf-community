import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.fundraising.donors.index', params)
        return await api.get(url)
    },
    async getCount (date) {
        const url = `${route('api.fundraising.donors.count')}?date=${date}`
        return await api.get(url)
    },
    async getCountries (date) {
        const url = `${route('api.fundraising.donors.countries')}?date=${date}`
        return await api.get(url)
    },
    async getLanguages (date) {
        const url = `${route('api.fundraising.donors.languages')}?date=${date}`
        return await api.get(url)
    }
}
