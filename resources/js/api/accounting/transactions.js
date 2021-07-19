import { api, route } from "@/api/baseApi";
export default {
    async find(id, params = {}) {
        let url = route("api.accounting.transactions.show", {
            transaction: id,
            ...params
        });
        return await api.get(url);
    },
    async locations(params) {
        const url = route("api.accounting.transactions.locations", params);
        return await api.get(url);
    },
    async markControlled(transaction) {
        const url = route(
            "api.accounting.transactions.markControlled",
            transaction
        );
        return await api.post(url);
    },
    async undoControlled(transaction) {
        const url = route(
            "api.accounting.transactions.undoControlled",
            transaction
        );
        return await api.delete(url);
    }
};
