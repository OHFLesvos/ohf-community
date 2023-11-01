<template>
    <b-container v-if="supplier">
        <SupplierForm
            :supplier="supplier"
            :title="$t('Edit supplier')"
            :disabled="isBusy"
            @submit="updateSupplier"
            @cancel="handleCancel"
            @delete="deleteSupplier"
        />
        <p class="text-right">
            <small>
                {{ $t("Last updated") }}:
                {{ supplier.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import suppliersApi from "@/api/accounting/suppliers";
import SupplierForm from "@/components/accounting/SupplierForm.vue";
export default {
    title() {
        return this.$t("Edit supplier");
    },
    components: {
        SupplierForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            supplier: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetchSupplier();
        }
    },
    async created() {
        this.fetchSupplier();
    },
    methods: {
        async fetchSupplier() {
            try {
                let data = await suppliersApi.find(this.id);
                this.supplier = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async updateSupplier(formData) {
            this.isBusy = true;
            try {
                let data = await suppliersApi.update(this.id, formData);
                showSnackbar(this.$t("Supplier updated."));
                this.$router.push({
                    name: "accounting.suppliers.show",
                    parms: { id: data.data.slug }
                });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async deleteSupplier() {
            this.isBusy = true;
            try {
                await suppliersApi.delete(this.id);
                showSnackbar(this.$t("Supplier deleted."));
                this.$router.push({ name: "accounting.suppliers.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({
                name: "accounting.suppliers.show",
                parms: { id: this.supplier.slug }
            });
        }
    }
};
</script>
