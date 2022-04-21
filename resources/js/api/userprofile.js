import { api, route } from "@/api/baseApi";
export default {
    async list() {
        const url = route("api.userprofile");
        return await api.get(url);
    },
    async updateProfile(data) {
        const url = route('api.userprofile.update')
        return await api.post(url, data)
    },
    async updatePassword(data) {
        const url = route('api.userprofile.updatePassword')
        return await api.post(url, data)
    },
    async delete() {
        const url = route('api.userprofile.delete')
        return await api.delete(url)
    },
    async view2FA() {
        const url = route('api.userprofile.2fa.index')
        return await api.get(url)
    },
    async store2FA(code) {
        const url = route('api.userprofile.2fa.store')
        return await api.post(url, {
            code: code
        })
    },
};
