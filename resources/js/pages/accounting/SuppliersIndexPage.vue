<template>
    <base-table
        ref="table"
        id="suppliers-table"
        :fields="fields"
        :api-method="fetchData"
        default-sort-by="name"
        :empty-text="$t('app.no_data_registered')"
        :items-per-page="25"
    >
        <template v-slot:cell(name)="data">
            <b-link
                v-if="data.item.can_update"
                :to="{ name: 'accounting.suppliers.show', params: { id: data.item.id } }"
            >
                {{ data.value }}
            </b-link>
            <template v-else>{{ data.value }}</template>
        </template>
        <template v-slot:cell(contact)="data">
            <phone-link
                v-if="data.item.phone"
                :value="data.item.phone"
                icon-only/>
            <phone-link
                v-if="data.item.mobile"
                :value="data.item.mobile"
                icon-only
                mobile/>
            <email-link
                v-if="data.item.email"
                :value="data.item.email"
                icon-only/>
        </template>
    </base-table>
</template>

<script>
import suppliersApi from '@/api/accounting/suppliers'
import BaseTable from '@/components/table/BaseTable'
import PhoneLink from '@/components/common/PhoneLink'
import EmailLink from '@/components/common/EmailLink'
export default {
    components: {
        BaseTable,
        EmailLink,
        PhoneLink
    },
    data () {
        return {
            fields: [
                {
                    key: 'name',
                    label: this.$t('app.name'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'category',
                    label: this.$t('app.category'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'transactions_count',
                    label: this.$t('accounting.transactions'),
                    tdClass: 'align-middle',
                    class: 'fit text-right d-none d-md-table-cell'
                },                
                {
                    key: 'contact',
                    label: this.$t('app.contact'),
                    class: 'fit',
                    tdClass: 'align-middle'
                }
            ]
        }
    },
    methods: {
        async fetchData (ctx) {
            return suppliersApi.list(ctx)
        },
    }
}
</script>