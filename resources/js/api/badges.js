import { api, route } from "@/api/baseApi";
export default {
    async make(elements, altLogo) {
        const formData = new FormData();
        elements.forEach(function(element, idx) {
            formData.append(`elements[${idx}][name]`, element.name)
            formData.append(`elements[${idx}][position]`, element.position)
            if (element.picture) {
                formData.append(`elements[${idx}][picture]`, element.picture)
            }
        });
        if (altLogo) {
            formData.append('alt_logo', altLogo)
        }

        const url = route("api.badges.make");
        return await api.download(url, "POST", formData);
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
