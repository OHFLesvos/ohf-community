import { api, route } from "@/api/baseApi";
export default {
    async list() {
        const url = route("api.dashboard");
        return await api.get(url);
    }
};
