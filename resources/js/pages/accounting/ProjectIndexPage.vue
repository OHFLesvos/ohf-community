<template>
    <b-container class="px-0">
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <nested-list-group
            :items="tree"
            class="mb-4"
            @itemClick="navigateToEdit"
        />
    </b-container>
</template>

<script>
import projectsApi from "@/api/accounting/projects";
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
                this.tree = await projectsApi.tree();
            } catch (err) {
                this.errorText = err;
            }
        },
        navigateToEdit(id) {
            this.$router.push({
                name: "accounting.projects.edit",
                params: { id: id }
            });
        }
    }
};
</script>
