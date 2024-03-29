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
    async generateMembershipNumber (visitorId) {
        const url = route('api.visitors.generateMembershipNumber', visitorId)
        return await api.post(url, {})
    },
    async signLiabilityForm (visitorId) {
        const url = route('api.visitors.signLiabilityForm', visitorId)
        return await api.post(url, {})
    },
    async giveParentalConsent (visitorId) {
        const url = route('api.visitors.giveParentalConsent', visitorId)
        return await api.post(url, {})
    },
    // Reporting
    async visitorCheckins (date_start, date_end, granularity, purpose) {
        let params = {
            date_start: date_start,
            date_end: date_end,
            granularity: granularity,
            purpose: purpose,
        }
        const url = route('api.visitors.report.visitorCheckins', params)
        return await api.get(url)
    },
    async ageDistribution (date_start, date_end, purpose) {
        const params = {
            date_start: date_start,
            date_end: date_end,
            purpose: purpose,
        }
        const url = route('api.visitors.report.ageDistribution', params)
        return await api.get(url)
    },
    async genderDistribution (date_start, date_end, purpose) {
        const params = {
            date_start: date_start,
            date_end: date_end,
            purpose: purpose,
        }
        const url = route('api.visitors.report.genderDistribution', params)
        return await api.get(url)
    },
    async nationalityDistribution (date_start, date_end, purpose) {
        const params = {
            date_start: date_start,
            date_end: date_end,
            purpose: purpose,
        }
        const url = route('api.visitors.report.nationalityDistribution', params)
        return await api.get(url)
    },
    async checkInsByPurpose(date_start, date_end) {
        const params = {
            date_start: date_start,
            date_end: date_end,
        }
        const url = route('api.visitors.report.checkInsByPurpose', params)
        return await api.get(url)
    },
    async export(params) {
        const url = route("api.visitors.export", params);
        return await api.download(url);
    }
}
