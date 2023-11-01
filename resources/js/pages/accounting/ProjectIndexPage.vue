<template>
    <b-container>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div v-if="loaded">
            <NestedListGroup
                v-if="tree.length > 0"
                :items="tree"
                :header="$t('Projects')"
                class="mb-4"
                @itemClick="navigateToEdit"
            />
            <b-alert v-else variant="info" show>{{
                $t("No entries registered.")
            }}</b-alert>
            <ButtonGroup :items="[
                {
                    to: { name: 'accounting.projects.create' },
                    variant: 'primary',
                    icon: 'plus-circle',
                    text: $t('Add'),
                    show: can('configure-accounting')
                },
            ]"/>
        </div>
        <template v-else>
            {{ $t('Loading...') }}
        </template>
    </b-container>
</template>

<script>
import projectsApi from "@/api/accounting/projects";
import NestedListGroup from "@/components/ui/NestedListGroup.vue";
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";
import ButtonGroup from "@/components/common/ButtonGroup.vue";
export default {
    title() {
        return this.$t("Projects");
    },
    components: {
        NestedListGroup,
        AlertWithRetry,
        ButtonGroup
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
