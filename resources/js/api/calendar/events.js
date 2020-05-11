import { api, route } from '@/api/baseApi'
export default {
    async list () {
        const url = route('api.calendar.events.index')
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.calendar.events.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.calendar.events.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.calendar.events.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.calendar.events.destroy', id)
        return await api.delete(url)
    }
}
