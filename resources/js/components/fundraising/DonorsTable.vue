<template>
    <base-table
        ref="table"
        :id="id"
        :fields="fields"
        :api-method="fetchData"
        default-sort-by="first_name"
        :empty-text="$t('fundraising.no_donors_found')"
        :filter-placeholder="`${$t('fundraising.search_for_name_address_email_phone')}...`"
        :items-per-page="25"
    >
        <!-- Cells -->
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

        <!-- Tags -->
        <template v-slot:top>
            <p
                v-if="Object.keys(tags).length > 0"
                class="mb-3 d-flex align-items-center"
            >
                <span class="mr-2">{{ $t('app.tags') }}:</span>
                <tag-select-button
                    v-for="(tag_name, tag_key) in tags"
                    :key="tag_key"
                    :label="tag_name"
                    :value="tag_key"
                    :toggled="tagSelected(tag_key)"
                    @toggled="toggleTag"
                />
            </p>
        </template>

    </base-table>
</template>

<script>
import moment from 'moment'
import PhoneLink from '@/components/common/PhoneLink'
import EmailLink from '@/components/common/EmailLink'
import BaseTable from '@/components/table/BaseTable'
import donorsApi from '@/api/fundraising/donors'
import TagSelectButton from '@/components/tags/TagSelectButton'
export default {
    components: {
        BaseTable,
        EmailLink,
        PhoneLink,
        TagSelectButton
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
        let id = 'donorsTable'
        return {
            id: id,
            fields: [
                {
                    key: 'first_name',
                    label: this.$t('app.first_name'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'last_name',
                    label: this.$t('app.last_name'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'company',
                    label: this.$t('app.company'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'street',
                    label: this.$t('app.street'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle'
                },
                {
                    key: 'zip',
                    label: this.$t('app.zip'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle'
                },
                {
                    key: 'city',
                    label: this.$t('app.city'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'country',
                    label: this.$t('app.country'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'language',
                    label: this.$t('app.correspondence_language'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'contact',
                    label: this.$t('app.contact'),
                    class: 'fit',
                    tdClass: 'align-middle'
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    class: 'd-none d-sm-table-cell fit',
                    tdClass: 'align-middle',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: value => {
                        return moment(value).fromNow()
                    }
                },
            ],
            selectedTags: this.getSelectedTags(id)
        }
    },
    methods: {
        fetchData (params) {
            params.tags = []
            for (let i = 0; i < this.selectedTags.length; i++) {
                params.tags.push(this.selectedTags[i])
            }
            return donorsApi.list(params)
        },
        getSelectedTags (id) {
            let tags
            if (this.tag) {
                tags = [this.tag]
                sessionStorage.setItem(id + '.selectedTags', JSON.stringify(tags))
            } else if (sessionStorage.getItem(id + '.selectedTags')) {
                tags = JSON.parse(sessionStorage.getItem(id + '.selectedTags'))
            } else {
                tags = []
            }
            return tags.filter(e => Object.keys(this.tags).indexOf(e) >= 0)
        },
        toggleTag (value, toggled) {
            this.selectedTags = this.selectedTags.filter((v) => v != value)
            if (toggled) {
                this.selectedTags.push(value)
            }
            sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(this.selectedTags))
            this.$refs.table.refresh()
        },
        tagSelected (key) {
            return this.selectedTags.indexOf(key) >= 0
        }
    }
}
</script>
