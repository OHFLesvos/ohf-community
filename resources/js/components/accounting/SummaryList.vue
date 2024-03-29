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
                <router-link
                    v-if="can('view-transactions')"
                    :to="{
                        name: 'accounting.transactions.index',
                        query: {
                            wallet: wallet || undefined,
                            [`filter[${paramName}]`]: data.item.id
                                ? data.item.id
                                : data.value,
                            'filter[date_start]': filterDateStart,
                            'filter[date_end]': filterDateEnd
                        }
                    }"
                >
                    {{ data.value }}
                </router-link>
                <template v-else>{{ data.value }}</template>
            </template>
            <em v-else>{{ noNameLabel }}</em>
        </template>
    </b-table>
</template>

<script>
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
        return {
            fields: [
                {
                    key: "name",
                    label: this.title
                },
                !this.flattenChildren
                    ? {
                          key: "amount_formatted",
                          label: this.$t("Amount"),
                          class: "text-right",
                          tdClass: (value, key, item) =>
                              this.colorClass(item.amount)
                      }
                    : null,
                this.flattenChildren
                    ? {
                          key: "total_amount_formatted",
                          label: this.$t("Total Amount"),
                          class: "text-right",
                          formatter: (value, key, item) =>
                              item.amount != 0 && item.amount_formatted != value
                                  ? value + " (" + item.amount_formatted + ")"
                                  : value,
                          tdClass: (value, key, item) =>
                              this.colorClass(item.total_amount)
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
        colorClass(value) {
            if (value > 0) {
                return "text-success";
            }
            if (value < 0) {
                return "text-danger";
            }
            return null;
        },
        fillTree(tree, elem, level = 0) {
            tree.push({
                id: elem.id,
                name: elem.name,
                donations: elem.donations,
                prefix: level > 0 ? "&nbsp;".repeat(level * 5) : "",
                amount: elem.amount,
                amount_formatted: elem.amount_formatted,
                total_amount: elem.total_amount,
                total_amount_formatted: elem.total_amount_formatted
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1);
            }
        }
    }
};
</script>
