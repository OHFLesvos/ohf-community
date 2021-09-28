import { api, route } from "@/api/baseApi";
export default {
    async list(params) {
        const url = route("api.accounting.transactions.index", params);
        return await api.get(url);
    },
    async history(params) {
        let url = route("api.accounting.transactions.history", params);
        return await api.get(url);
    },
    async find(id, params = {}) {
        let url = route("api.accounting.transactions.show", {
            transaction: id,
            ...params
        });
        return await api.get(url);
    },
    async transactionHistory(id) {
        let url = route("api.accounting.transactions.transactionHistory", id);
        return await api.get(url);
    },
    async store(wallet, data) {
        const url = route("api.accounting.transactions.store", wallet);
        return await api.post(url, data);
    },
    async update(id, data) {
        const url = route("api.accounting.transactions.update", id);
        return await api.put(url, data);
    },
    async updateReceipt(transaction, files) {
        const formData = new FormData();
        Array.from(files).forEach(file => formData.append("img[]", file));
        const url = route(
            "api.accounting.transactions.updateReceipt",
            transaction
        );
        return await api.postFormData(url, formData);
    },
    async rotateReceipt(transaction, picture, direction) {
        const url = route(
            "api.accounting.transactions.rotateReceipt",
            transaction
        );
        return await api.put(url, { picture: picture, direction: direction });
    },
    async delete(id) {
        const url = route("api.accounting.transactions.destroy", id);
        return await api.delete(url);
    },
    async locations(params) {
        const url = route("api.accounting.transactions.locations", params);
        return await api.get(url);
    },
    async secondaryCategories(params) {
        const url = route(
            "api.accounting.transactions.secondaryCategories",
            params
        );
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
    async taxonomies(params) {
        const url = route("api.accounting.transactions.taxonomies", params);
        return await api.get(url);
    },
    async listControllable(params) {
        const url = route("api.accounting.transactions.controllable", params);
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
    },
    async export(params) {
        const url = route("api.accounting.transactions.export", params);
        return await api.download(url);
    }
};
