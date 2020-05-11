import { api, route } from '@/api/baseApi'
export default {
    async listTransactions (params) {
        const url = route('api.bank.withdrawal.transactions', params)
        return await api.get(url)
    }
}
