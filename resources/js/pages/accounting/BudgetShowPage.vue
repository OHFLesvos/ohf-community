<template>
    <b-container v-if="budget" class="px-0">
        <dl class="row">
            <dt class="col-sm-3">{{ $t("Name") }}</dt>
            <dd class="col-sm-9">{{ budget.name }}</dd>
            <template v-if="budget.description">
                <dt class="col-sm-3">{{ $t("Description") }}</dt>
                <dd class="col-sm-9">
                    <nl2br tag="span" :text="budget.description" />
                </dd>
            </template>
            <dt class="col-sm-3">{{ $t("Agreed amount") }}</dt>
            <dd class="col-sm-9">
                {{ budget.agreed_amount | decimalNumberFormat }}
            </dd>
            <template v-if="budget.initial_amount">
                <dt class="col-sm-3">{{ $t("Initial amount") }}</dt>
                <dd class="col-sm-9">
                    {{ budget.initial_amount | decimalNumberFormat }}
                </dd>
            </template>
            <dt class="col-sm-3">{{ $t("Balance") }}</dt>
            <dd class="col-sm-9">{{ budget.balance | decimalNumberFormat }}</dd>
            <template v-if="budget.donor">
                <dt class="col-sm-3">{{ $t("Donor") }}</dt>
                <dd class="col-sm-9">
                    <router-link
                        v-if="can('view-fundraising-entities')"
                        :to="{
                            name: 'fundraising.donors.show',
                            params: { id: budget.donor_id }
                        }"
                    >
                        {{ budget.donor.full_name }}
                    </router-link>
                    <template v-else>
                        {{ budget.donor.full_name }}
                    </template>
                </dd>
            </template>
            <template v-if="budget.closed_at">
                <dt class="col-sm-3">{{ $t("Closing date") }}</dt>
                <dd class="col-sm-9">{{ budget.closed_at | dateFormat }}</dd>
            </template>
            <template v-if="budget.is_completed">
                <dt class="col-sm-3">{{ $t("Completed") }}</dt>
                <dd class="col-sm-9">{{ $t("Yes") }}</dd>
            </template>
        </dl>
        <base-table
            v-if="can('view-transactions')"
            id="budget-transactions-table"
            :fields="transactioFields"
            :api-method="fetchTransactions"
            default-sort-by="created_at"
            :default-sort-desc="true"
            :empty-text="$t('No transactions found.')"
            :items-per-page="25"
            no-filter
        >
            <template v-slot:cell(receipt_no)="data">
                <router-link
                    :to="{
                        name: 'accounting.transactions.show',
                        params: { id: data.item.id }
                    }"
                >
                    {{ data.value }}
                </router-link>
            </template>

            <template v-slot:cell(supplier)="data">
                <router-link
                    v-if="data.item.supplier"
                    :to="{
                        name: 'accounting.suppliers.show',
                        params: { id: data.item.supplier.slug }
                    }"
                    :title="data.item.supplier.category"
                >
                    {{ data.item.supplier.name }}
                </router-link>
            </template>
        </base-table>
        <p>
            <router-link
                v-if="budget.can_update"
                :to="{
                    name: 'accounting.budgets.edit',
                    params: { id: id }
                }"
                class="btn btn-primary"
            >
                <font-awesome-icon icon="edit" /> {{ $t("Edit") }}</router-link
            >
            <b-dropdown :disabled="isBusy">
                <template #button-content>
                    <font-awesome-icon
                        :icon="isBusy ? 'spinner' : 'download'"
                        :spin="isBusy"
                    />
                    {{ $t("Export") }}
                </template>
                <b-dropdown-item @click="exportFile()"
                    >{{ $t('Spreadsheet only') }}</b-dropdown-item
                >
                <b-dropdown-item @click="exportFile({ include_pictures: true })"
                    >{{ $t('Spreadsheet and pictures') }}</b-dropdown-item
                >
            </b-dropdown>
            <router-link
                :to="{
                    name: 'accounting.budgets.index'
                }"
                class="btn btn-secondary"
            >
                <font-awesome-icon icon="times-circle" />
                {{ $t("Overview") }}
            </router-link>
        </p>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import budgetsApi from "@/api/accounting/budgets";
import BaseTable from "@/components/table/BaseTable";
import { can } from "@/plugins/laravel";
import Nl2br from "vue-nl2br";
export default {
    components: {
        BaseTable,
        Nl2br
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            isBusy: false,
            budget: null,
            transactions: [],
            transactioFields: [
                {
                    key: "receipt_no",
                    label: this.$t("Receipt")
                },
                {
                    key: "date",
                    label: this.$t("Date"),
                    formatter: this.dateFormat
                },
                {
                    key: "amount",
                    label: this.$t("Amount"),
                    class: "fit text-right",
                    tdClass: (value, key, item) =>
                        item.type == "income" ? "text-success" : "text-danger",
                    formatter: (value, key, item) =>
                        (item.type == "spending" ? "-" : "") +
                        this.decimalNumberFormat(value)
                },
                {
                    key: "category_full_name",
                    label: this.$t("Category")
                },
                {
                    key: "description",
                    label: this.$t("Description")
                },
                {
                    key: "supplier",
                    label: this.$t("Supplier")
                },
                {
                    key: "created_at",
                    label: this.$t("Registered"),
                    formatter: this.dateTimeFormat
                }
            ]
        };
    },
    watch: {
        $route() {
            this.fetch();
        }
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await budgetsApi.find(this.id);
                this.budget = data.data;
            } catch (err) {
                alert(err);
                console.error(err);
            }
        },
        async fetchTransactions() {
            return await budgetsApi.transactions(this.id);
        },
        can,
        async exportFile(params) {
            this.isBusy = true;
            try {
                await budgetsApi.export(this.id, params);
            } catch (err) {
                alert(err);
                console.error(err);
            }
            this.isBusy = false;
        }
    }
};
</script>
