import { api, route } from '@/api/baseApi'
export default {
    async list (params) {
        const url = route('api.cmtyvol.index', params)
        return await api.get(url)
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
    }
}
