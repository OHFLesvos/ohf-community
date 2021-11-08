import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.visitors.index', params)
        return await api.get(url)
    },
    async checkin (visitor) {
        const url = route('api.visitors.checkin', visitor)
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
