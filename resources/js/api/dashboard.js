import { api, route } from "@/api/baseApi";
export default {
    async systemInfo() {
        const url = route("api.system-info");
        return await api.get(url);
    },
    async changelog() {
        const url = route("api.changelog");
        return await api.get(url);
    }
};
