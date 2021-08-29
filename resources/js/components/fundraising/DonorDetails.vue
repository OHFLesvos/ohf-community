<template>
    <b-list-group
        v-if="donor"
        flush
    >
        <two-col-list-group-item
            v-if="donor.salutation"
            :title="$t('Salutation')"
        >
            {{ donor.salutation }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.first_name || donor.last_name"
            :title="$t('Name')"
        >
            {{ donor.first_name }} {{ donor.last_name }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.company"
            :title="$t('Company')"
        >
            {{ donor.company }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.fullAddress"
            :title="$t('Address')"
        >
            <span style="white-space: pre;">{{ donor.fullAddress }}</span>
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.email"
            :title="$t('E-Mail Address')"
        >
            <email-link :value="donor.email" />
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.phone"
            :title="$t('Phone')"
        >
            <phone-link :value="donor.phone" />
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.language"
            :title="$t('Correspondence language')"
        >
            {{ donor.language }}
        </two-col-list-group-item>

        <two-col-list-group-item
            :title="$t('Registered')"
        >
            {{ donor.created_at | dateTimeFormat }}
            <small class="text-muted pl-2">{{  donor.created_at | timeFromNow }}</small>
        </two-col-list-group-item>

        <two-col-list-group-item
            :title="$t('Last updated')"
        >
            {{ donor.updated_at | dateTimeFormat }}
            <small class="text-muted pl-2">{{  donor.updated_at | timeFromNow }}</small>
        </two-col-list-group-item>

        <two-col-list-group-item :title="$t('Tags')">
            <tag-manager
                :key="id"
                :list-provider="listTags"
                :store-provider="donor.can_create_tag ? storeTags : null"
                :suggestions-provider="listAllTags"
                preload
                @tag-click="$router.push({ name: 'fundraising.donors.index', query: { tag: $event } })"
            >
                {{ $t('Loading...') }}
            </tag-manager>
        </two-col-list-group-item>
    </b-list-group>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import donorsApi from '@/api/fundraising/donors'
import TwoColListGroupItem from '@/components/ui/TwoColListGroupItem'
import PhoneLink from '@/components/common/PhoneLink'
import EmailLink from '@/components/common/EmailLink'
import TagManager from '@/components/tags/TagManager'
export default {
    components: {
        TwoColListGroupItem,
        EmailLink,
        PhoneLink,
        TagManager
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            donor: null
        }
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            try {
                let data = await donorsApi.find(this.id, true)
                this.donor = data.data
            } catch (err) {
                alert(err)
            }
        },
        listAllTags: donorsApi.listTags,
        listTags () {
            return donorsApi.listDonorsTags(this.id)
        },
        storeTags (data) {
            return donorsApi.storeDonorsTags(this.id, data)
        }
    }
}
</script>
