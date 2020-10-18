import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.accounting.wallets.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.accounting.wallets.store')
        return await api.post(url, data)
    },
    async find (id, params = {}) {
        let url = route('api.accounting.wallets.show', { wallet: id, ...params })
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.accounting.wallets.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.accounting.wallets.destroy', id)
        return await api.delete(url)
    },
    // async transactions (id, params = {}) {
    //     const url = route('api.accounting.wallets.transactions', { wallet: id, ...params})
    //     return await api.get(url)
    // },
}
