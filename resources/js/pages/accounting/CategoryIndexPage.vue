<template>
    <b-container>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div v-if="loaded">
            <NestedListGroup
                v-if="tree.length > 0"
                :items="tree"
                :header="$t('Categories')"
                class="mb-4"
                @itemClick="navigateToEdit"
            />
            <b-alert v-else variant="info" show>{{
                $t("No entries registered.")
            }}</b-alert>
            <ButtonGroup :items="[
                {
                    to: { name: 'accounting.categories.create' },
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
import categoriesApi from "@/api/accounting/categories";
import NestedListGroup from "@/components/ui/NestedListGroup.vue";
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";
import ButtonGroup from "@/components/common/ButtonGroup.vue";
export default {
    title() {
        return this.$t("Categories");
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
                this.tree = await categoriesApi.tree();
                this.loaded = true;
            } catch (err) {
                this.errorText = err;
            }
        },
        navigateToEdit(id) {
            this.$router.push({
                name: "accounting.categories.show",
                params: { id: id }
            });
        }
    }
};
</script>
