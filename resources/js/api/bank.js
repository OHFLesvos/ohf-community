import { api, route } from '@/api/baseApi'
export default {
    async listTransactions (params) {
        const url = route('api.bank.withdrawal.transactions', params)
        return await api.get(url)
    },
    async fetchDailyStats () {
        const url = route('api.bank.withdrawal.dailyStats')
        return await api.get(url)
    },
    async searchPersons (filter, page) {
        const params = {
            filter: filter,
            page: page
        }
        const url = route('api.bank.withdrawal.search', params)
        return await api.get(url)
    },
    async findPerson (id) {
        const url = route('api.bank.withdrawal.person', id)
        return await api.get(url)
    },
    async handoutCoupon (personId, couponId, data) {
        const url = route('api.bank.withdrawal.handoutCoupon', [personId, couponId])
        return await api.post(url, data)
    },
    async undoHandoutCoupon (personId, couponId) {
        const url = route('api.bank.withdrawal.handoutCoupon', [personId, couponId])
        return await api.delete(url)
    },
    async fetchVisitorReportData () {
        const url = route('api.bank.reporting.visitors')
        return await api.get(url)
    },
    async fetchWithdrawalReportData () {
        const url = route('api.bank.reporting.withdrawals')
        return await api.get(url)
    }
}
