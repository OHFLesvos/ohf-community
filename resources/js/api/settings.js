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
        const formData = new FormData();
        Object.entries(data).forEach(e => formData.append(e[0], e[1]));
        const url = route("api.settings.update");
        return await api.postFormData(url, formData);
    },
    async reset() {
        const url = route("api.settings.reset");
        return await api.delete(url);
    },
    async resetField(field) {
        const url = route("api.settings.resetField", {key: field});
        return await api.delete(url);
    },
};
