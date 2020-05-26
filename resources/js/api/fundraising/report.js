import { api, route } from '@/api/baseApi'
export default {
    async getCount (date) {
        const url = `${route('api.fundraising.report.donors.count')}?date=${date}`
        return await api.get(url)
    },
    async getCountries (date) {
        const url = `${route('api.fundraising.report.donors.countries')}?date=${date}`
        return await api.get(url)
    },
    async getLanguages (date) {
        const url = `${route('api.fundraising.report.donors.languages')}?date=${date}`
        return await api.get(url)
    },
    async fetchDonorRegistrations (granularity, dateFrom, dateTo) {
        const params = {
            granularity: granularity,
            from: dateFrom,
            to: dateTo
        }
        const url = route('api.fundraising.report.donors.registrations', params)
        return await api.get(url)
    },
    async fetchDonationRegistrations (granularity, dateFrom, dateTo) {
        const params = {
            granularity: granularity,
            from: dateFrom,
            to: dateTo
        }
        const url = route('api.fundraising.report.donations.registrations', params)
        return await api.get(url)
    },
    async fechCurrencyDistribution () {
        const url = route('api.fundraising.report.donations.currencies')
        return await api.get(url)
    },
    async fetchChannelDistribution () {
        const url = route('api.fundraising.report.donations.channels')
        return await api.get(url)
    },
}
