import { api, route } from '@/api/baseApi'
export default {
    async find (id) {
        const url = route('api.comments.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.comments.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.comments.destroy', id)
        return await api.delete(url)
    }
}
