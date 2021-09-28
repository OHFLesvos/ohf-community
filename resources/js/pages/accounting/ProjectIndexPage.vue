<template>
    <b-container class="px-0">
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <nested-list-group
            v-if="tree.length > 0"
            :items="tree"
            class="mb-4"
            @itemClick="navigateToEdit"
        />
        <b-alert v-else-if="loaded" variant="info" show>{{
            $t("No entries registered.")
        }}</b-alert>
        <template v-else>
            {{ $t('Loading...') }}
        </template>
    </b-container>
</template>

<script>
import projectsApi from "@/api/accounting/projects";
import NestedListGroup from "@/components/ui/NestedListGroup";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
export default {
    title() {
        return this.$t("Projects");
    },
    components: {
        NestedListGroup,
        AlertWithRetry
    },
    data() {
        return {
            tree: [],
            errorText: null,
            loaded: false,
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
                this.loaded = true;
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
