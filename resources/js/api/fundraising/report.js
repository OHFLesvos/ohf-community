import { api, route } from '@/api/baseApi'
export default {
    async getCount (date) {
        const url = `${route('api.fundraising.report.donors.count')}?date=${date}`
        return await api.get(url)
    },
    async getCountries (date) {
        const url = `${route('api.fundraising.report.donors.countries')}?date=${date}`
        return await api.get(url)
    },
    async getLanguages (date) {
        const url = `${route('api.fundraising.report.donors.languages')}?date=${date}`
        return await api.get(url)
    }
}
