import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.fundraising.donations.index', params)
        return await api.get(url)
    },
    async find (id) {
        let url = route('api.fundraising.donations.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.fundraising.donations.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.fundraising.donations.destroy', id)
        return await api.delete(url)
    },
    async listChannels () {
        const url = route('api.fundraising.donations.channels')
        return await api.get(url)
    },
    async listCurrencies () {
        const url = route('api.fundraising.donations.currencies')
        return await api.get(url)
    },
    async import (type, file) {
        const formData = new FormData();
        formData.append('type', type)
        formData.append('file', file)
        const url = route('api.fundraising.donations.import')
        return await api.postFormData(url, formData)
    },
    async export(params) {
        const url = route("api.fundraising.donations.export", params);
        return await api.get(url);
    },
}
