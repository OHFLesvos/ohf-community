import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.people.index', params)
        return await api.get(url)
    },
    async find (id) {
        const url = route('api.people.show', id)
        return await api.get(url)
    }
}
