import { api, route } from '@/api/baseApi'
export default {
    async findAll () {
        const url = route('api.kb.articles.index')
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.kb.articles.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.kb.articles.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.kb.articles.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.kb.articles.destroy', id)
        return await api.delete(url)
    }
}
