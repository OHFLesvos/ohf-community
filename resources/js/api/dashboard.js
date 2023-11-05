import { api, route } from "@/api/baseApi";
export default {
    async list() {
        const url = route("api.dashboard");
        return await api.get(url);
    },
    async systemInfo() {
        const url = route("api.system-info");
        return await api.get(url);
    }
};
