import { api, route } from "@/api/baseApi";
export default {
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
    async updateReceipt(transaction, files) {
        const formData = new FormData();
        Array.from(files).forEach(file => formData.append('img[]', file));
        const url = route("api.accounting.transactions.updateReceipt", transaction);
        return await api.postFormData(url, formData);
    },
    async locations(params) {
        const url = route("api.accounting.transactions.locations", params);
        return await api.get(url);
    },
    async secondaryCategories(params) {
        const url = route("api.accounting.transactions.secondaryCategories", params);
        return await api.get(url);
    },
    async costCenters(params) {
        const url = route("api.accounting.transactions.costCenters", params);
        return await api.get(url);
    },
    async attendees(params) {
        const url = route("api.accounting.transactions.attendees", params);
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
