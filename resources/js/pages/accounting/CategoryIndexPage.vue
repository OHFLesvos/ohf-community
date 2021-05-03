<template>
    <div>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <nested-list-group :items="tree" class="mb-4" @itemClick="navigateToEdit" />
    </div>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import NestedListGroup from "@/components/ui/NestedListGroup";
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
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
