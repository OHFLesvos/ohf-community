import { api, route } from "@/api/baseApi";
export default {
    async listPeriods(wallet) {
        const url = route("api.accounting.webling.periods", { wallet: wallet });
        return await api.get(url);
    },
    async prepare(wallet, period, from, to) {
        const url = route("api.accounting.webling.prepare", { wallet, period, from, to });
        return await api.get(url);
    },
};
