import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.fundraising.donors.index', params)
        return await api.get(url)
    },
    async listDonations (donorId, year = null) {
        let url = route('api.fundraising.donors.donations', donorId)
        if (year) {
            url += `?year=${year}`
        }
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
