import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.fundraising.donations.index', params)
        return await api.get(url)
    },
    async store (donorId, data) {
        const url = route('api.fundraising.donations.store', donorId)
        return await api.post(url, data)
    }
}
