<template>
    <div class="card shadow-sm mb-4">
        <div class="card-header">{{ title }}</div>
        <table class="table table-strsiped mb-0">
            <tbody>
                <template v-if="items.length > 0">
                    <tr v-for="v in items" :key="v.id">
                        <td>
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
                                >
                                    {{ v.name }}
                                </a>
                                <template v-else>
                                    {{ v.name }}
                                </template>
                            </template>
                            <em v-else>{{ noNameLabel }}</em>
                        </td>
                        <td
                            class="text-right"
                            :class="colorClass(v.amount > 0)"
                        >
                            {{ numberFormat(v.amount) }}
                        </td>
                    </tr>
                </template>
                <tr v-else>
                    <td>
                        <em>{{
                            $t("No data available in the selected time range.")
                        }}</em>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
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
        filterDateEnd: {}
    },
    data() {
        return {};
    },
    methods: {
        can,
        colorClass(value) {
            return value > 0 ? "text-success" : "text-danger";
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
        }
    }
};
</script>
