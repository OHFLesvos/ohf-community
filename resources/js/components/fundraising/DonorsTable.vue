<template>
    <base-table
        id="donorsTable"
        :fields="fields"
        :api-url="route('api.fundraising.donors.index')"
        :tags="tags"
        :tag="tag"
        default-sort-by="first_name"
        :empty-text="$t('fundraising.no_donors_found')"
        :filter-placeholder="`${$t('fundraising.search_for_name_address_email_phone')}...`"
        :items-per-page="25"
    >
        <template v-slot:cell(first_name)="data">
            <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template v-slot:cell(last_name)="data">
            <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template v-slot:cell(company)="data">
            <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template v-slot:cell(contact)="data">
            <email-link v-if="data.item.email" :value="data.item.email" icon-only></email-link>
            <phone-link v-if="data.item.phone" :value="data.item.phone" icon-only></phone-link>
        </template>
    </base-table>
</template>

<script>
import moment from 'moment'
import PhoneLink from '@/components/PhoneLink'
import EmailLink from '@/components/EmailLink'
import BaseTable from '@/components/BaseTable'
export default {
    components: {
        BaseTable,
        EmailLink,
        PhoneLink
    },
    props: {
        tags: {
            require: false,
            type: Object,
            default: () => {
                return {}
            }
        },
        tag: {
            require: false,
            type: String,
            default: null
        }
    },
    data() {
        return {
            fields: [
                {
                    key: 'first_name',
                    label: this.$t('app.first_name'),
                    sortable: true
                },
                {
                    key: 'last_name',
                    label: this.$t('app.last_name'),
                    sortable: true
                },
                {
                    key: 'company',
                    label: this.$t('app.company'),
                    sortable: true
                },
                {
                    key: 'street',
                    label: this.$t('app.street'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'zip',
                    label: this.$t('app.zip'),
                    class: 'd-none d-sm-table-cell'
                },
                {
                    key: 'city',
                    label: this.$t('app.city'),
                    class: 'd-none d-sm-table-cell',
                    sortable: true
                },
                {
                    key: 'country',
                    label: this.$t('app.country'),
                    class: 'd-none d-sm-table-cell',
                    sortable: true
                },
                {
                    key: 'language',
                    label: this.$t('app.correspondence_language'),
                    class: 'd-none d-sm-table-cell',
                    sortable: true
                },
                {
                    key: 'contact',
                    label: this.$t('app.contact'),
                    class: 'fit'
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    class: 'd-none d-sm-table-cell fit',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: value => {
                        return moment(value).fromNow()
                    }
                },
            ]
        }
    }
}
</script>
