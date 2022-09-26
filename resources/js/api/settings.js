import { api, route } from "@/api/baseApi";
export default {
    async list() {
        const url = route("api.settings");
        return await api.get(url);
    },
    async fields() {
        const url = route("api.settings.fields");
        return await api.get(url);
    },
    async update(data) {
        const url = route("api.settings.update");
        return await api.put(url, data);
    },
    async reset() {
        const url = route("api.settings.reset");
        return await api.delete(url);
    },
};
