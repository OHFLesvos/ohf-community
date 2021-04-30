import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.accounting.categories.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.accounting.categories.store')
        return await api.post(url, data)
    },
    async find (id, params = {}) {
        let url = route('api.accounting.categories.show', { category: id, ...params })
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.accounting.categories.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.accounting.categories.destroy', id)
        return await api.delete(url)
    },
}
