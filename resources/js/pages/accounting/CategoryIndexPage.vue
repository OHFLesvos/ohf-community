<template>
    <b-container class="px-0">
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <nested-list-group
            v-if="tree.length > 0"
            :items="tree"
            class="mb-4"
            @itemClick="navigateToEdit"
        />
        <b-alert v-else variant="info" show>{{
            $t("No entries registered.")
        }}</b-alert>
    </b-container>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import NestedListGroup from "@/components/ui/NestedListGroup";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
export default {
    components: {
        NestedListGroup,
        AlertWithRetry
    },
    data() {
        return {
            tree: [],
            errorText: null
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.errorText = null;
            try {
                this.tree = await categoriesApi.tree();
            } catch (err) {
                this.errorText = err;
            }
        },
        navigateToEdit(id) {
            this.$router.push({
                name: "accounting.categories.edit",
                params: { id: id }
            });
        }
    }
};
</script>
