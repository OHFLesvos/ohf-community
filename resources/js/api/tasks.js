import { api, route } from '@/api/baseApi'
export default {
    async list () {
        const url = route('api.tasks.index')
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.tasks.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.tasks.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.tasks.update', id)
        return await api.put(url, data)
    },
    async done (id) {
        const url = route('api.tasks.done', id)
        return await api.patch(url)
    },
    async delete (id) {
        const url = route('api.tasks.destroy', id)
        return await api.delete(url)
    }
}
