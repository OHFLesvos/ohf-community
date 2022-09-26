<template>
    <base-table
        ref="table"
        id="transactions-history-table"
        :fields="fields"
        :api-method="entries"
        :empty-text="$t('No entries found.')"
        :items-per-page="10"
        default-sort-by="created_at"
        :default-sort-desc="true"
        no-filter
    >
        <template v-slot:cell(transaction_id)="data">
            <router-link
                :to="{
                    name: 'accounting.transactions.show',
                    params: { id: data.value }
                }"
            >
                {{ data.value }}
            </router-link>
        </template>
        <template #cell(changes)="data">
            <ul class="mb-0 list-unstyled">
                <li v-for="(change, key) in data.value" :key="key">
                    {{ key }}:
                    <code v-if="data.item.event !== 'created'">{{
                        change.old !== null ? change.old : "null"
                    }}</code>
                    <font-awesome-icon
                        v-if="data.item.event === 'updated'"
                        icon="long-arrow-alt-right"
                    />
                    <code v-if="data.item.event !== 'deleted'">{{
                        change.new !== null ? change.new : "null"
                    }}</code>
                </li>
            </ul>
        </template>
    </base-table>
</template>

<script>
import BaseTable from "@/components/table/BaseTable.vue";
export default {
    components: {
        BaseTable
    },
    props: {
        entries: {
            required: true
        },
        showId: Boolean
    },
    data() {
        return {
            fields: [
                {
                    key: "created_at",
                    label: this.$t("Date"),
                    formatter: this.dateTimeFormat
                },
                {
                    key: "user",
                    label: this.$t("User"),
                    formatter: user => {
                        if (user == null) {
                            return null;
                        }
                        return user.name;
                    }
                },
                {
                    key: "event",
                    label: this.$t("Action"),
                    formatter: event => {
                        if (event == "created") {
                            return this.$t("Created");
                        }
                        if (event == "updated") {
                            return this.$t("Updated");
                        }
                        if (event == "deleted") {
                            return this.$t("Deleted");
                        }
                        return event;
                    },
                    tdClass: (event) => {
                        if (event == "created") {
                            return 'text-success';
                        }
                        if (event == "updated") {
                            return 'text-info';
                        }
                        if (event == "deleted") {
                            return 'text-danger';
                        }
                        return null;
                    }
                },
                this.showId
                    ? {
                          key: "transaction_id",
                          label: this.$t("Transaction ID"),
                          class: "fit text-right"
                      }
                    : null,
                {
                    key: "changes",
                    label: this.$t("Changes")
                }
            ]
        };
    },
    methods: {
        refresh() {
            this.$refs.table.refresh();
        }
    }
};
</script>
