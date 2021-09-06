import { api, route } from "@/api/baseApi";
export default {
    async list(params) {
        const url = route("api.accounting.budgets.index", params);
        return await api.get(url);
    },
    async store(data) {
        const url = route("api.accounting.budgets.store");
        return await api.post(url, data);
    },
    async find(id, params = {}) {
        let url = route("api.accounting.budgets.show", {
            budget: id,
            ...params
        });
        return await api.get(url);
    },
    async update(id, data) {
        const url = route("api.accounting.budgets.update", id);
        return await api.put(url, data);
    },
    async delete(id) {
        const url = route("api.accounting.budgets.destroy", id);
        return await api.delete(url);
    },
    async transactions(id, params = {}) {
        const url = route("api.accounting.budgets.transactions", {
            budget: id,
            ...params
        });
        return await api.get(url);
    },
    async donations(id, params = {}) {
        const url = route("api.accounting.budgets.donations", {
            budget: id,
            ...params
        });
        return await api.get(url);
    },
    async names() {
        const url = route("api.accounting.budgets.names");
        return await api.get(url);
    },
    async export(id, params) {
        const url = route("api.accounting.budgets.export", { budget: id, ...params });
        return await api.download(url);
    }
};
