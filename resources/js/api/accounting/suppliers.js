import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.accounting.suppliers.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.accounting.suppliers.store')
        return await api.post(url, data)
    },
    async find (id) {
        let url = route('api.accounting.suppliers.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.accounting.suppliers.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.accounting.suppliers.destroy', id)
        return await api.delete(url)
    },
    async transactions (id, params = {}) {
        const url = route('api.accounting.suppliers.transactions', { supplier: id, ...params})
        return await api.get(url)
    },
}
