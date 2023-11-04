import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.roles.index', params)
        return await api.get(url)
    },
    async listWithUsers(params) {
        if (!params) {
            params = {}
        }
        params.include = 'users'
        const url = route('api.roles.index', params)
        return await api.get(url)
    },
    async store (data) {
        const url = route('api.roles.store')
        return await api.post(url, data)
    },
    async find (id) {
        const url = route('api.roles.show', id)
        return await api.get(url)
    },
    async update (id, data) {
        const url = route('api.roles.update', id)
        return await api.put(url, data)
    },
    async delete (id) {
        const url = route('api.roles.destroy', id)
        return await api.delete(url)
    },
    async permissions() {
        const url = route('api.roles.permissions')
        return await api.get(url)
    },
}
