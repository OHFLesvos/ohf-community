<template>
    <b-list-group flush>
        <two-col-list-group-item
            v-if="donor.salutation"
            :title="$t('app.salutation')"
        >
            {{ donor.salutation }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.first_name || donor.last_name"
            :title="$t('app.salutation')"
        >
            {{ donor.first_name }} {{ donor.last_name }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.company"
            :title="$t('app.company')"
        >
            {{ donor.company }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.fullAddress"
            :title="$t('app.address')"
        >
            <span style="white-space: pre;">{{ donor.fullAddress }}</span>
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.email"
            :title="$t('app.email')"
        >
            <email-link :value="donor.email" />
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.phone"
            :title="$t('app.phone')"
        >
            <phone-link :value="donor.phone" />
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="donor.language"
            :title="$t('app.correspondence_language')"
        >
            <phone-link :value="donor.language" />
        </two-col-list-group-item>

        <two-col-list-group-item
            :title="$t('app.registered')"
        >
            {{ moment(donor.created_at).format('LLL') }}
            <small class="text-muted pl-2">{{  moment(donor.created_at).fromNow() }}</small>
        </two-col-list-group-item>

        <two-col-list-group-item
            :title="$t('app.last_updated')"
        >
            {{ moment(donor.updated_at).format('LLL') }}
            <small class="text-muted pl-2">{{  moment(donor.updated_at).fromNow() }}</small>
        </two-col-list-group-item>

        <two-col-list-group-item :title="$t('app.tags')">
            <tag-manager
                :api-list-url="route('api.fundraising.donors.tags.index', donor.id)"
                :api-store-url="donor.can_create_tag ? route('api.fundraising.donors.tags.store', donor.id) : null"
                :api-suggestions-url="route('api.fundraising.tags.index')"
                :tag-url="route('fundraising.donors.index', { 'tag': '' })"
                preload
            >
                {{ $t('app.loading') }}
            </tag-manager>
        </two-col-list-group-item>
    </b-list-group>
</template>

<script>
import moment from 'moment'
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
        donor: {
            type: Object,
            required: true
        }
    },
    methods: {
        moment
    }
}
</script>
