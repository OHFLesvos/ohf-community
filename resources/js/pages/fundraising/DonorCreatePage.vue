<template>
    <b-container>
        <donor-form
            :disabled="isBusy"
            :title="$t('Add donor')"
            @submit="registerDonor"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import donorsApi from "@/api/fundraising/donors";
import { showSnackbar } from "@/utils";
import DonorForm from "@/components/fundraising/DonorForm.vue";
export default {
    title() {
        return this.$t("Add donor");
    },
    components: {
        DonorForm
    },
    data() {
        return {
            isBusy: false
        };
    },
    methods: {
        async registerDonor(formData) {
            this.isBusy = true;
            try {
                let data = await donorsApi.store(formData);
                showSnackbar(data.message);
                this.$router.push({
                    name: "fundraising.donors.show",
                    params: { id: data.id }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "fundraising.donors.index" });
        }
    }
};
</script>
