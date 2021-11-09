<template>
    <b-container>
        <h2 class="display-4 mb-3">{{ $t("Register new visitor") }}</h2>
        <VisitorForm
            :disabled="isBusy"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import visitorsApi from "@/api/visitors";
import VisitorForm from "@/components/visitors/VisitorForm";
import { showSnackbar } from "@/utils";
export default {
    components: {
        VisitorForm,
    },
    data() {
        return {
            isBusy: false,
        };
    },
    methods: {
        async handleCreate(formData) {
            this.isBusy = true;
            try {
                await visitorsApi.store(formData);
                showSnackbar(this.$t("Visitor registered."));
                this.$router.push({ name: "visitors.check_in" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "visitors.check_in" });
        },
    },
};
</script>
