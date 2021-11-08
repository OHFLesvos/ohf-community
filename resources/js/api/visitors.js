import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.visitors.index', params)
        return await api.get(url)
    },
    async checkin (data) {
        const url = route('api.visitors.checkin')
        return await api.post(url, data)
    },
    async checkout (id) {
        const url = route('api.visitors.checkout', id)
        return await api.put(url)
    },
    async checkoutAll () {
        const url = route('api.visitors.checkoutAll')
        return await api.post(url)
    },
    async dailyVisitors () {
        const url = route('api.visitors.dailyVisitors')
        return await api.get(url)
    },
    async monthlyVisitors () {
        const url = route('api.visitors.monthlyVisitors')
        return await api.get(url)
    }
}
