import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.cmtyvol.responsibilities.index', params)
        return await api.get(url)
    },
    async find (id) {
        let url = route('api.cmtyvol.responsibilities.show', id)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.cmtyvol.responsibilities.store')
        return await api.post(url, data)
    },
    async update (id, data) {
        const url = route('api.cmtyvol.responsibilities.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.cmtyvol.responsibilities.destroy', id)
        return await api.delete(url)
    },
}
