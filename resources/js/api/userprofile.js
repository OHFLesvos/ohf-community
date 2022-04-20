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
};
