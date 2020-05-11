import { api, route } from '@/api/baseApi'
export default {
    async list () {
        const url = route('api.calendar.resources.index')
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.calendar.resources.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.calendar.resources.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.calendar.resources.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.calendar.resources.destroy', id)
        return await api.delete(url)
    }
}
