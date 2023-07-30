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
    async signLiabilityForm (visitorId) {
        const url = route('api.visitors.signLiabilityForm', visitorId)
        return await api.post(url, {})
    },
    async giveParentalConsent (visitorId) {
        const url = route('api.visitors.giveParentalConsent', visitorId)
        return await api.post(url, {})
    },
    async dailyVisitors (params = {}) {
        const url = route('api.visitors.dailyVisitors', params)
        return await api.get(url)
    },
    async monthlyVisitors () {
        const url = route('api.visitors.monthlyVisitors')
        return await api.get(url)
    },
    async dailyRegistrations (granularity, dateFrom, dateTo) {
        const params = {
            from: dateFrom,
            to: dateTo,
            granularity: granularity,
        }
        const url = route('api.visitors.dailyRegistrations', params)
        return await api.get(url)
    },
    async ageDistribution (params) {
        const url = route('api.visitors.ageDistribution', params)
        return await api.get(url)
    },
    async nationalityDistribution (params) {
        const url = route('api.visitors.nationalityDistribution', params)
        return await api.get(url)
    },
    async checkInsByVisitor (params) {
        const url = route('api.visitors.checkInsByVisitor', params)
        return await api.get(url)
    },
    async checkInsByPurpose(granularity, dateFrom, dateTo) {
        const params = {
            from: dateFrom,
            to: dateTo,
            granularity: granularity
        }
        const url = route('api.visitors.checkInsByPurpose', params)
        return await api.get(url)
    }
}
