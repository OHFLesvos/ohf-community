<template>
    <b-container fluid>
        <div v-if="cmtyvol">
            <PageHeader :title="cmtyvol.full_name" :buttons="pageHeaderButtons"/>
            <div class="card-columns">
                <template v-for="section in sections">
                    <b-card v-if="fields.filter(f => f.section == section.name && f.value).length" :header="section.title" no-body :key="section.name">
                        <b-list-group flush>
                            <template v-for="field in fields.filter(f => f.section == section.name)">
                                <b-list-group-item v-if="field.value" :key="field.label">
                                    <b-row>
                                        <b-col lg="5">
                                            <font-awesome-icon v-if="field.icon" :icon="field.icon"/>
                                            <strong>{{ field.label }}</strong>
                                        </b-col>
                                        <b-col lg>
                                            <template v-if="field.type == 'phone'">
                                                <PhoneLink :value="field.value"/>
                                            </template>
                                            <template v-else-if="field.type == 'email'">
                                                <EmailLink :value="field.value"/>
                                            </template>
                                            <template v-else-if="field.type == 'whatsapp'">
                                                <span v-html="field.value"/>
                                            </template>
                                            <template v-else-if="field.type == 'image'">
                                                <img :src="field.value" class="img-fluid img-thumbnail">
                                            </template>
                                            <template v-else-if="field.type == 'responsibilities'">
                                                <div v-for="(r, k) in field.value" :key="k">
                                                    {{ k }}
                                                    <font-awesome-icon v-if="r.description" icon="info-circle" :title="r.description"/>
                                                    <small v-if="r.start_date && r.end_date">
                                                        ({{ dateFormat(r.start_date) }} - {{ dateFormat(r.end_date) }})
                                                    </small>
                                                    <small v-else-if="r.start_date">({{ $t('since {date}', { date: dateFormat(r.start_date) }) }})</small>
                                                    <small v-else-if="r.end_date">({{ $t('until {date}', { date: dateFormat(r.end_date) }) }})</small>
                                                </div>
                                            </template>
                                            <template v-else>
                                                {{ field.value }}
                                            </template>
                                        </b-col>
                                    </b-row>
                                </b-list-group-item>
                            </template>
                        </b-list-group>
                    </b-card>
                </template>
                <b-card :header="$t('Comments')" body-class="pb-0">
                    <CmtyvolComments :id="id" />
                </b-card>
            </div>
        </div>
        <div v-else>
            {{ $t('Loading...') }}
        </div>
    </b-container>
</template>

<script>
import CmtyvolComments from "@/components/cmtyvol/CmtyvolComments.vue";
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
import PhoneLink from "@/components/common/PhoneLink.vue";
import EmailLink from "@/components/common/EmailLink.vue";
import PageHeader from "@/components/layout/PageHeader.vue";
export default {
    title() {
        return this.$t("Community Volunteer");
    },
    components: {
        CmtyvolComments,
        EmailLink,
        PhoneLink,
        PageHeader
    },
    props: {
        id: {
            required: true,
        },
    },
    data() {
        return {
            cmtyvol: null,
            sections: [
                {
                    name: 'portrait',
                    title: this.$t('Portrait'),
                },
                {
                    name: 'general',
                    title: this.$t('Common'),
                },
                {
                    name: 'reachability',
                    title: this.$t('Reachability'),
                },
                {
                    name: 'occupation',
                    title: this.$t('Occupation'),
                },
            ],
        }
    },
    computed: {
        pageHeaderButtons() {
            console.log(this.cmtyvol.can_update)
            return [
                {
                    to: {
                        name: "cmtyvol.edit",
                        params: { id: this.id }
                    },
                    variant: "primary",
                    icon: "pencil-alt",
                    text: this.$t("Edit"),
                    show: this.cmtyvol.can_update
                },
            ]
        },
        fields() {
            if (!this.cmtyvol) {
                return []
            }
            return [
                {
                    section: 'portrait',
                    label: this.$t('Portrait Picture'),
                    value: this.cmtyvol.portrait_picture_url,
                    type: 'image',
                },
                {
                    section: 'general',
                    label: this.$t('First Name'),
                    value: this.cmtyvol.first_name
                },
                {
                    section: 'general',
                    label: this.$t('Family Name'),
                    value: this.cmtyvol.family_name
                },
                {
                    section: 'general',
                    label: this.$t('Nickname'),
                    value: this.cmtyvol.nickname
                },
                {
                    section: 'general',
                    label: this.$t('Nationality'),
                    value: this.cmtyvol.nationality,
                    icon: 'globe',
                },
                {
                    section: 'general',
                    label: this.$t('Gender'),
                    value: this.genderLabel
                },
                {
                    section: 'general',
                    label: this.$t('Date of birth'),
                    value: this.cmtyvol.date_of_birth
                },
                {
                    section: 'general',
                    label: this.$t('Age'),
                    value: this.cmtyvol.age
                },
                {
                    section: 'general',
                    label: this.$t('Police Number'),
                    value: this.cmtyvol.police_no,
                    icon: 'id-card',
                },
                {
                    section: 'general',
                    label: this.$t('Languages'),
                    value: this.cmtyvol.languages?.join(', '),
                    icon: 'language',
                },
                {
                    section: 'reachability',
                    label: this.$t('Local Phone'),
                    value: this.cmtyvol.local_phone,
                    icon: 'phone',
                    type: 'phone',
                },
                {
                    section: 'reachability',
                    label: this.$t('Other Phone'),
                    value: this.cmtyvol.other_phone,
                    icon: 'phone',
                    type: 'phone',
                },
                {
                    section: 'reachability',
                    label: this.$t('WhatsApp'),
                    value: this.cmtyvol.whatsapp_link,
                    icon: 'fa-brands fa-whatsapp',
                    type: 'whatsapp',
                },
                {
                    section: 'reachability',
                    label: this.$t('Email address'),
                    value: this.cmtyvol.email,
                    icon: 'envelope',
                    type: 'email',
                },
                {
                    section: 'reachability',
                    label: this.$t('Skype'),
                    value: this.cmtyvol.skype,
                    icon: 'fa-brands fa-skype',
                },
                {
                    section: 'reachability',
                    label: this.$t('Residence'),
                    value: this.cmtyvol.residence
                },
                {
                    section: 'reachability',
                    label: this.$t('Pickup location'),
                    value: this.cmtyvol.pickup_location
                },
                {
                    section: 'occupation',
                    label: this.$t('Responsibilities'),
                    value: this.cmtyvol.responsibilities,
                    type: 'responsibilities',
                },
                {
                    section: 'occupation',
                    label: this.$t('Starting Date'),
                    value: this.cmtyvol.first_work_start_date ? `${this.dateFormat(this.cmtyvol.first_work_start_date)} (${this.timeFromNow(this.cmtyvol.first_work_start_date)})` : null
                },
                {
                    section: 'occupation',
                    label: this.$t('Leaving Date'),
                    value: this.cmtyvol.last_work_end_date ? `${this.dateFormat(this.cmtyvol.last_work_end_date)} (${this.timeFromNow(this.cmtyvol.last_work_end_date)})` : null
                },
                {
                    section: 'occupation',
                    label: this.$t('Working since (days)'),
                    value: this.cmtyvol.working_since_days
                },
                {
                    section: 'general',
                    label: this.$t('Notes'),
                    value: this.cmtyvol.notes
                },
            ]
        },
        genderLabel() {
            switch (this.cmtyvol.gender) {
                case "m":
                case "male":
                    return this.$t("male");
                case "f":
                case "female":
                    return this.$t("female");
                case "other":
                default:
                    return this.$t("other");
            }
        },
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            let data = await cmtyvolApi.find(this.id)
            this.cmtyvol = data.data
        }
    }
};
</script>
