<template>
    <b-table
        hover
        :items="flattenChildren ? flattenedItems : items"
        :fields="fields"
        :show-empty="true"
        :empty-text="$t('No data available in the selected time range.')"
        class="shadow-sm mb-4 bg-white"
    >
        <template #cell(name)="data">
            <span v-if="data.item.prefix" v-html="data.item.prefix"></span>
            <template v-if="data.value">
                <a
                    v-if="wallet && can('view-transactions')"
                    :href="
                        route('accounting.transactions.index', {
                            wallet,
                            [`filter[${paramName}]`]: data.item.id
                                ? data.item.id
                                : data.value,
                            'filter[date_start]': filterDateStart,
                            'filter[date_end]': filterDateEnd
                        })
                    "
                    >{{ data.value }}</a
                >
                <template v-else>{{ data.value }}</template>
            </template>
            <em v-else>{{ noNameLabel }}</em>
        </template>
    </b-table>
</template>

<script>
import numeral from "numeral";
import { can } from "@/plugins/laravel";
export default {
    props: {
        items: {
            type: Array,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        noNameLabel: {},
        paramName: {},
        wallet: {},
        filterDateStart: {},
        filterDateEnd: {},
        flattenChildren: Boolean,
        showDonations: Boolean
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.title
                },
                !this.flattenChildren
                    ? {
                          key: "amount",
                          label: this.$t("Amount"),
                          class: "text-right",
                          formatter: (value, key, item) =>
                              this.numberFormat(value),
                          tdClass: (value, key, item) =>
                              this.colorClass(value > 0)
                      }
                    : null,
                this.flattenChildren
                    ? {
                          key: "total_amount",
                          label: this.$t("Total Amount"),
                          class: "text-right",
                          formatter: (value, key, item) =>
                              item.amount != value
                                  ? "(" + this.numberFormat(value) + ")"
                                  : this.numberFormat(value),
                          tdClass: (value, key, item) =>
                              this.colorClass(value > 0)
                      }
                    : null,
                this.showDonations
                    ? {
                          key: "donations",
                          label: this.$t("Donations"),
                          class: "text-right"
                      }
                    : null
            ]
        };
    },
    computed: {
        flattenedItems() {
            let arr = [];
            for (let elem of this.items) {
                this.fillTree(arr, elem);
            }
            return arr;
        }
    },
    methods: {
        can,
        colorClass(value) {
            return value > 0 ? "text-success" : "text-danger";
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
        },
        fillTree(tree, elem, level = 0) {
            tree.push({
                id: elem.id,
                name: elem.name,
                donations: elem.donations,
                prefix: level > 0 ? "&nbsp;".repeat(level * 5) : "",
                amount: elem.amount,
                total_amount: elem.total_amount
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1);
            }
        }
    }
};
</script>
