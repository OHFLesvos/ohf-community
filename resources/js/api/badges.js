import { api, route } from "@/api/baseApi";
export default {
    async make(params) {
        const url = route("api.badges.make", params);
        return await api.download(url, "POST");
    },
    async fetchCommunityVolunteers() {
        const url = route("api.badges.fetchCommunityVolunteers");
        return await api.get(url);
    },
    async parseSpreadsheet(file) {
        const formData = new FormData();
        formData.append('file', file)
        const url = route("api.badges.parseSpreadsheet");
        return await api.postFormData(url, formData);
    }
};
