import { api, route } from "@/api/baseApi";
export default {
    async settings() {
        const url = route("api.accounting.settings");
        return await api.get(url);
    },
    async list(wallet, params) {
        const url = route("api.accounting.transactions.index", {
            wallet,
            ...params
        });
        return await api.get(url);
    },
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
    },
    async undoBooking(transaction) {
        const url = route(
            "api.accounting.transactions.undoBooking",
            transaction
        );
        return await api.put(url);
    }
};
