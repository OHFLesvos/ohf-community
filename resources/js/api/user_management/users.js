import { api, route } from '@/api/baseApi'
export default {
    async list(params) {
        const url = route('api.users.index', params)
        return await api.get(url)
    },
    async listWithRoles(params) {
        if (!params) {
            params = {}
        }
        params.include = 'roles'
        const url = route('api.users.index', params)
        return await api.get(url)
    },
    async store(data) {
        const url = route('api.users.store')
        return await api.post(url, data)
    },
    async find(id) {
        let params = {
            user: id,
            include: 'roles,administeredRoles'
        }
        const url = route('api.users.show', params)
        return await api.get(url)
    },
    async update(id, data) {
        const url = route('api.users.update', id)
        return await api.put(url, data)
    },
    async delete(id) {
        const url = route('api.users.destroy', id)
        return await api.delete(url)
    },
    async disableOAuth(id) {
        const url = route('api.users.disableOAuth', id)
        return await api.put(url)
    },
    async disable2FA(id) {
        const url = route('api.users.disable2FA', id)
        return await api.put(url)
    },
}
