<template>
    <b-container fluid>
        <PageHeader :title="supplier?.name ?? '...'"/>
        <TabNav :items="tabNavItems">
            <template v-slot:after(transactions)>
                <b-badge v-if="transactionsCount > 0" class="ml-1">
                    {{ transactionsCount }}
                </b-badge>
            </template>
        </TabNav>
        <router-view />
    </b-container>
</template>

<script>
import TabNav from "@/components/layout/TabNav.vue";
import PageHeader from "@/components/layout/PageHeader.vue";
import suppliersApi from "@/api/accounting/suppliers";
export default {
    title() {
        return this.$t("View supplier");
    },
    components: {
        TabNav,
        PageHeader,
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            transactionsCount: null,
            tabNavItems: [
                {
                    to: { name: "accounting.suppliers.show" },
                    icon: "info",
                    text: this.$t("Details")
                },
                {
                    to: { name: "accounting.suppliers.show.transactions" },
                    icon: "list",
                    text: this.$t("Transactions"),
                    key: "transactions"
                }
            ],
            supplier: null
        };
    },
    watch: {
        $route() {
            this.fetchData();
        }
    },
    async created() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                let data = await suppliersApi.find(this.id);
                this.transactionsCount = data.data.transactions_count;
                this.supplier = data.data;
            } catch (err) {
                this.error = err;
            }
        }
    }
};
</script>
