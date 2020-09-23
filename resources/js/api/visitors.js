import { api, route } from '@/api/baseApi'
export default {
    async listCurrent (ctx) {
        const url = route('api.visitors.listCurrent', ctx)
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
    }
}
