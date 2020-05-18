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
    },
    async listComments (donorId) {
        const url = route('api.fundraising.donors.comments.index', donorId)
        return await api.get(url)
    },
    async storeComment (donorId, data) {
        const url = route('api.fundraising.donors.comments.store', donorId)
        return await api.post(url, data)
    }
}
