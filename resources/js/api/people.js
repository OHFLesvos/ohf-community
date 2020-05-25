import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.people.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.people.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.people.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.people.update', id)
        return await api.put(url, data)
    },
    async updateGender (id, value) {
        const url = route('api.people.updateGender', id)
        return await api.patch(url,  {
            gender: value
        })
    },
    async updateDateOfBirth (id, value) {
        const url = route('api.people.updateDateOfBirth', id)
        return await api.patch(url,  {
            date_of_birth: value
        })
    },
    async updateNationality (id, value) {
        const url = route('api.people.updateNationality', id)
        return await api.patch(url,  {
            nationality: value
        })
    },
    async updateCardNo (id, value) {
        const url = route('api.people.registerCard', id)
        return await api.patch(url,  {
            card_no: value
        })
    },
    async updatePoliceNo (id, value) {
        const url = route('api.people.updatePoliceNo', id)
        return await api.patch(url,  {
            police_no: value
        })
    },
    async updateRemarks (id, value) {
        const url = route('api.people.updateRemarks', id)
        return await api.patch(url,  {
            remarks: value
        })
    },
    async fetchMonthlySummaryReportData (year, month) {
        let params = {}
        if (year && month) {
            params = {
                year: year,
                month: month
            }
        }
        const url = route('api.people.reporting.monthlySummary', params)
        return await api.get(url)
    },
    async fetchNumberReportData (from, to) {
        const url = route('api.people.reporting.numbers', {
            from: from,
            to: to
        })
        return await api.get(url)
    },
    async fetchAgeDistribution () {
        const url = route('api.people.reporting.ageDistribution')
        return await api.get(url)
    },
    async fetchNationalityDistribution () {
        const url = route('api.people.reporting.nationalities')
        return await api.get(url)
    },
    async fetchGenderDistribution () {
        const url = route('api.people.reporting.genderDistribution')
        return await api.get(url)
    },
    async fetchRegistrationsPerDay (dateFrom, dateTo) {
        const url = route('api.people.reporting.registrationsPerDay', {
            from: dateFrom,
            to: dateTo
        })
        return await api.get(url)
    }
}
