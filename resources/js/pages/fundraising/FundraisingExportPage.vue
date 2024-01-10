<template>
    <b-container>

        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-card :title="$t('Export')">
                <b-form-group :label="$t('File format')" class="mb-3">
                    <b-form-radio-group
                        v-model="format"
                        :options="formatOptions"
                        required
                        stacked
                    />
                </b-form-group>
                <b-form-group :label="$t('Type')" class="mb-3">
                    <b-form-radio-group
                        v-model="type"
                        :options="typeOptions"
                        required
                        stacked
                    />
                </b-form-group>
                <b-form-group :label="type == 'donors' ? $t('Show donations of year') : $t('Year')" class="mb-3">
                    <b-form-radio-group
                        v-model="year"
                        :options="yearOptions"
                        required
                        stacked
                    />
                </b-form-group>
                <div v-if="type == 'donors'">
                    <b-form-checkbox v-model="includeChannels">
                        {{ $t("Include channels and currencies") }}
                    </b-form-checkbox>
                    <b-form-checkbox v-model="showAllDonors">
                        {{ $t("Include all donors which did not donate in the selected year") }}
                    </b-form-checkbox>
                </div>
                <div v-if="type == 'donations'">
                    <b-form-checkbox v-model="includeAddress">
                        {{ $t("Include address of donor") }}
                    </b-form-checkbox>
                </div>

                <template #footer>
                    <template v-if="isBusy">
                        <b-spinner class="align-middle mr-2"></b-spinner>
                        {{ $t("Generating file...") }}
                    </template>
                    <template v-else>
                        <b-button variant="primary" @click="handleSubmit()">
                            {{ $t("Export") }}
                        </b-button>
                    </template>
                </template>
            </b-card>
        </form>

    </b-container>
</template>
<script>
import donorsApi from "@/api/fundraising/donors";
import donationsApi from "@/api/fundraising/donations";

export default {
    data() {
        return {
            isBusy: false,
            format: "xlsx",
            formats: {
                xlsx: this.$t("Excel (.xlsx)"),
            },
            type: 'donors',
            typeOptions: [
                {value: 'donors', text: this.$t('Donors')},
                {value: 'donations', text: this.$t('Donations')},
            ],
            includeChannels: false,
            showAllDonors: false,
            includeAddress: false,
            years: [],
            year: null
        };
    },
    computed: {
        formatOptions() {
            return Object.entries(this.formats).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        yearOptions() {
            if (this.type == 'donors') {
                return [
                    {value: null, text: this.$t('Current and last year')},
                    ...this.years.map(e => ({
                        value: e,
                        text: e
                    }))
                ];
            }
            if (this.type == 'donations') {
                return [
                    {value: null, text: this.$t('All')},
                    ...this.years.map(e => ({
                        value: e,
                        text: e
                    }))
                ];
            }
            return []
        },
    },
    async created() {
        const res = await donationsApi.listYears();
        this.years = res.data
        this.years.sort((a,b)=>b-a);
        if (this.years.length) {
            this.year = this.years[0]
        }
    },
    methods: {
        async handleSubmit() {
            this.isBusy = true;
            try {
                if (this.type == 'donors') {
                    const params = {
                        format: this.format,
                        includeChannels: this.includeChannels,
                        showAllDonors: this.showAllDonors,
                        year: this.year,
                    };
                    await donorsApi.export(params)
                    // const url = this.route("api.fundraising.donors.export", params);
                    // window.location.href = url
                } else if (this.type == 'donations') {
                    const params = {
                        format: this.format,
                        includeAddress: this.includeAddress,
                        year: this.year,
                    };
                    await donationsApi.export(params)
                    // const url = this.route("api.fundraising.donations.export", params);
                    // window.location.href = url
                }
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
}
</script>
