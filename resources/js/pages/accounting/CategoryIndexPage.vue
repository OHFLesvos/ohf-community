<template>
    <div>
        <tree-view :items="tree" class="mb-4"/>
    </div>
</template>

<script>
import categoriesApi from "@/api/accounting/categories";
import TreeView from "@/components/accounting/TreeView";
export default {
    components: {
        TreeView
    },
    data() {
        return {
            tree: []
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.tree = await categoriesApi.tree();
        }
    }
};
</script>
