<template>
    <b-container v-if="visitor" class="mb-3">
        <b-alert v-if="visitor.anonymized" show variant="warning">{{
            $t("This record has been anonymized.")
        }}</b-alert>
        <VisitorForm
            :value="visitor"
            :disabled="isBusy || visitor.anonymized"
            :header="$t('Edit visitor')"
            @submit="handleUpdate"
            @delete="handleDelete"
            @cancel="handleCancel"
        />
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import visitorsApi from "@/api/visitors";
import VisitorForm from "@/components/visitors/VisitorForm.vue";
import { showSnackbar } from "@/utils";
export default {
    components: {
        VisitorForm,
    },
    props: {
        id: {
            required: true,
        },
    },
    data() {
        return {
            isBusy: false,
            visitor: null,
        };
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await visitorsApi.find(this.id);
                this.visitor = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await visitorsApi.update(this.id, formData);
                showSnackbar(this.$t("Visitor updated."));
                this.$router.push({ name: "visitors.check_in" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async handleDelete() {
            this.isBusy = true;
            try {
                await visitorsApi.delete(this.id);
                showSnackbar(this.$t("Visitor deleted."));
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
