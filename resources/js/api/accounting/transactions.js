import { api, route } from '@/api/baseApi'
export default {
    async locations (params) {
        const url = route('api.accounting.transactions.locations', params)
        return await api.get(url)
    },
}
