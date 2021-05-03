<template>
    <div>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <tree-view :items="tree" class="mb-4" @itemClick="navigateToEdit" />
    </div>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import TreeView from "@/components/accounting/TreeView";
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
export default {
    components: {
        TreeView,
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
