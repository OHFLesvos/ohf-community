import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.accounting.transactions.summary', params)
        return await api.get(url)
    },
}
