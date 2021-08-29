<template>
    <base-table
        ref="table"
        :id="id"
        :fields="fields"
        :api-method="fetchData"
        default-sort-by="first_name"
        :empty-text="$t('No donors found.')"
        :filter-placeholder="`${$t('Search for name, address, e-mail, phone')}...`"
        :items-per-page="25"
    >
        <!-- Name -->
        <template v-slot:cell(first_name)="data">
            <slot name="primary-cell" :value="data.value" :item="data.item">
                {{ data.value }}
            </slot>
        </template>
        <template v-slot:cell(last_name)="data">
            <slot name="primary-cell" :value="data.value" :item="data.item">
                {{ data.value }}
            </slot>
        </template>

        <!-- Company -->
        <template v-slot:cell(company)="data">
            <slot name="primary-cell" :value="data.value" :item="data.item">
                {{ data.value }}
            </slot>
        </template>

        <!-- Contact -->
        <template v-slot:cell(contact)="data">
            <email-link v-if="data.item.email" :value="data.item.email" icon-only></email-link>
            <phone-link v-if="data.item.phone" :value="data.item.phone" icon-only></phone-link>
        </template>

        <!-- Tags -->
        <template v-slot:top>
            <p
                v-if="myTags.length > 0"
                class="mb-3 d-flex align-items-center overflow-auto"
            >
                <span class="mr-2">{{ $t('Tags') }}:</span>
                <tag-select-button
                    v-for="tag in myTags"
                    :key="`${tag.value}-${tag.selected}`"
                    :label="tag.text"
                    :value="tag.value"
                    :toggled="tag.selected"
                    @toggled="toggleTag"
                />
            </p>
        </template>

    </base-table>
</template>

<script>
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
                    label: this.$t('First Name'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'last_name',
                    label: this.$t('Last Name'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'company',
                    label: this.$t('Company'),
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'street',
                    label: this.$t('Street'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle'
                },
                {
                    key: 'zip',
                    label: this.$t('ZIP'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle'
                },
                {
                    key: 'city',
                    label: this.$t('City'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'country_name',
                    label: this.$t('Country'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'language',
                    label: this.$t('Correspondence language'),
                    class: 'd-none d-sm-table-cell',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'contact',
                    label: this.$t('Contact'),
                    class: 'fit',
                    tdClass: 'align-middle'
                },
                {
                    key: 'created_at',
                    label: this.$t('Registered'),
                    class: 'd-none d-sm-table-cell fit',
                    tdClass: 'align-middle',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: this.timeFromNow
                },
            ],
            myTags: this.getSelectedTags(id)
        }
    },
    watch: {
        tag () {
            this.myTags = this.getSelectedTags(this.id)
        },
        myTags: {
            deep: true,
            handler () {
                this.$refs.table.refresh()
            }
        }
    },
    methods: {
        fetchData (params) {
            params.tags = this.myTags.filter(t => t.selected).map(t => t.value)
            return donorsApi.list(params)
        },
        getSelectedTags (id) {
            let selectedValues
            if (this.tag) {
                selectedValues = [this.tag]
                sessionStorage.setItem(id + '.selectedTags', JSON.stringify(selectedValues))
            } else if (sessionStorage.getItem(id + '.selectedTags')) {
                selectedValues = JSON.parse(sessionStorage.getItem(id + '.selectedTags'))
            } else {
                selectedValues = []
            }
            return Object.entries(this.tags).map(item => ({
                value: item[0],
                text: item[1],
                selected: selectedValues.indexOf(item[0]) >= 0
            }))
        },
        toggleTag (value, toggled) {
            let idx = this.myTags.findIndex((v) => v.value == value)
            this.myTags[idx].selected = toggled
            sessionStorage.setItem(this.id + '.selectedTags', JSON.stringify(this.myTags.filter(t => t.selected).map(t => t.value)))
        }
    }
}
</script>
