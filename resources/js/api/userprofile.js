import { api, route } from "@/api/baseApi";
export default {
    async list() {
        const url = route("api.userprofile");
        return await api.get(url);
    }
};
