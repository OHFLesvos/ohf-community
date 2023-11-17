import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.cmtyvol.index', params)
        return await api.get(url)
    },
    async find (id) {
        let url = route('api.cmtyvol.show', id)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.cmtyvol.store')
        return await api.post(url, data)
    },
    async update (id, data) {
        const url = route('api.cmtyvol.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.cmtyvol.destroy', id)
        return await api.delete(url)
    },
    async ageDistribution () {
        const url = route('api.cmtyvol.ageDistribution')
        return await api.get(url)
    },
    async genderDistribution () {
        const url = route('api.cmtyvol.genderDistribution')
        return await api.get(url)
    },
    async nationalityDistribution () {
        const url = route('api.cmtyvol.nationalityDistribution')
        return await api.get(url)
    },
    async listComments (donorId) {
        const url = route('api.cmtyvol.comments.index', donorId)
        return await api.get(url)
    },
    async storeComment (donorId, data) {
        const url = route('api.cmtyvol.comments.store', donorId)
        return await api.post(url, data)
    },
}
