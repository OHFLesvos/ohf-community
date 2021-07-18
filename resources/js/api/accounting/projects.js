import { api, route } from "@/api/baseApi";
export default {
    async list(params) {
        const url = route("api.accounting.projects.index", params);
        return await api.get(url);
    },
    async store(data) {
        const url = route("api.accounting.projects.store");
        return await api.post(url, data);
    },
    async find(id, params = {}) {
        let url = route("api.accounting.projects.show", {
            project: id,
            ...params
        });
        return await api.get(url);
    },
    async update(id, data) {
        const url = route("api.accounting.projects.update", id);
        return await api.put(url, data);
    },
    async delete(id) {
        const url = route("api.accounting.projects.destroy", id);
        return await api.delete(url);
    },
    async tree(params) {
        const url = route("api.accounting.projects.tree", params);
        return await api.get(url);
    }
};
