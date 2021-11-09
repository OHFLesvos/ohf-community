import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.visitors.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.visitors.store')
        return await api.post(url, data)
    },
    async find(id) {
        let url = route("api.visitors.show", id);
        return await api.get(url);
    },
    async update(id, data) {
        const url = route("api.visitors.update", id);
        return await api.put(url, data);
    },
    async delete(id) {
        const url = route("api.visitors.destroy", id);
        return await api.delete(url);
    },
    async checkins () {
        const url = route('api.visitors.checkins')
        return await api.get(url)
    },
    async checkin (visitorId, data) {
        const url = route('api.visitors.checkin', visitorId)
        return await api.post(url, data)
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
