<template>
    <base-table
        ref="table"
        id="suppliers-table"
        :fields="fields"
        :api-method="fetchData"
        default-sort-by="name"
        :empty-text="$t('No data registered.')"
        :items-per-page="25"
        :filter-placeholder="
            $t('Type to search ({fields})...', {
                fields: [
                    $t('Name'),
                    $t('Category'),
                    $t('Phone'),
                    $t('Tax number'),
                    $t('IBAN'),
                    $t('Remarks')
                ].join(', ')
            })
        "
    >
        <template v-slot:cell(name)="data">
            <router-link
                :to="{
                    name: 'accounting.suppliers.show',
                    params: { id: data.item.slug }
                }"
            >
                {{ data.value }}
            </router-link>
        </template>
        <template v-slot:cell(contact)="data">
            <phone-link
                v-if="data.item.phone"
                :value="data.item.phone"
                icon-only
            />
            <phone-link
                v-if="data.item.mobile"
                :value="data.item.mobile"
                icon-only
                mobile
            />
            <email-link
                v-if="data.item.email"
                :value="data.item.email"
                icon-only
            />
        </template>
    </base-table>
</template>

<script>
import suppliersApi from "@/api/accounting/suppliers";
import BaseTable from "@/components/table/BaseTable";
import PhoneLink from "@/components/common/PhoneLink";
import EmailLink from "@/components/common/EmailLink";
export default {
    components: {
        BaseTable,
        EmailLink,
        PhoneLink
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.$t("Name"),
                    sortable: true,
                    tdClass: "align-middle"
                },
                {
                    key: "category",
                    label: this.$t("Category"),
                    sortable: true,
                    tdClass: "align-middle"
                },
                {
                    key: "transactions_count",
                    label: this.$t("Transactions"),
                    tdClass: "align-middle",
                    class: "fit text-right d-none d-md-table-cell"
                },
                {
                    key: "contact",
                    label: this.$t("Contact"),
                    class: "fit",
                    tdClass: "align-middle"
                }
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return suppliersApi.list(ctx);
        }
    }
};
</script>
