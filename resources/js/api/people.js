import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.people.index', params)
        return await api.get(url)
    },
    async find (id) {
        const url = route('api.people.show', id)
        return await api.get(url)
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
    }
}
