import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.fundraising.donors.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.fundraising.donors.store')
        return await api.post(url, data)
    },
    async update (id, data) {
        const url = route('api.fundraising.donors.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.fundraising.donors.destroy', id)
        return await api.delete(url)
    },
    async listSalutations () {
        const url = route('api.fundraising.donors.salutations')
        return await api.get(url)
    },
    async listDonations (donorId) {
        let url = route('api.fundraising.donors.donations.index', donorId)
        return await api.get(url)
    },
    async storeDonation (donorId, data) {
        const url = route('api.fundraising.donors.donations.store', donorId)
        return await api.post(url, data)
    },
    async listComments (donorId) {
        const url = route('api.fundraising.donors.comments.index', donorId)
        return await api.get(url)
    },
    async storeComment (donorId, data) {
        const url = route('api.fundraising.donors.comments.store', donorId)
        return await api.post(url, data)
    },
    async listTags () {
        const url = route('api.fundraising.tags.index')
        return await api.get(url)
    }
}
