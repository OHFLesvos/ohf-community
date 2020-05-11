import { api, route } from '@/api/baseApi'
export default {
    /**
     * Gets a list of official languages (language code => localized name)
     */
    async listLanguages () {
        const url = route('api.languages')
        return await api.get(url)
    }
}
