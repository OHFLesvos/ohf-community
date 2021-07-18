<template>
    <b-card class="shadow-sm mb-4" :header="title" no-body>
        <b-table-simple hover class="mb-0">
            <b-tbody>
                <template v-if="items.length > 0">
                    <b-tr v-for="v in flattenChildren ? flattenedItems : items" :key="v.id">
                        <b-td>
                            <span v-if="v.prefix" v-html="v.prefix"></span>
                            <template v-if="v.name">
                                <a
                                    v-if="
                                        wallet && can('can-view-transactions')
                                    "
                                    :href="
                                        route('accounting.transactions.index', {
                                            wallet,
                                            [`filter[${paramName}]`]: v.id,
                                            'filter[date_start]': filterDateStart,
                                            'filter[date_end]': filterDateEnd
                                        })
                                    "
                                    >{{ v.name }}</a
                                >
                                <template v-else>{{ v.name }}</template>
                            </template>
                            <em v-else>{{ noNameLabel }}</em>
                        </b-td>
                        <b-td
                            class="text-right"
                            :class="colorClass(v.amount > 0)"
                        >
                            {{ numberFormat(v.amount) }}
                        </b-td>
                    </b-tr>
                </template>
                <b-tr v-else>
                    <b-td>
                        <em>{{
                            $t("No data available in the selected time range.")
                        }}</em>
                    </b-td>
                </b-tr>
            </b-tbody>
        </b-table-simple>
    </b-card>
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
        flattenChildren: Boolean
    },
    data() {
        return {};
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
                prefix: level > 0 ? "&nbsp;".repeat(level * 5) : "",
                amount: elem.total_amount
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1);
            }
        }
    }
};
</script>
