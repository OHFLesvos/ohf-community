<template>
    <div class="d-inline">
        <b-button variant="secondary" @click="modalShow = !modalShow">
            <font-awesome-icon icon="download" />
            <span class="d-none d-sm-inline">{{ $t("Export") }}</span>
        </b-button>
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-modal v-model="modalShow" :title="title" @ok="handleOk">
                <b-row>
                    <b-col sm>
                        <b-form-group :label="$t('File format')" class="mb-3">
                            <b-form-radio-group
                                v-model="format"
                                :options="formatOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                        <b-form-group :label="$t('Year')" class="mb-3">
                            <b-form-radio-group
                                v-model="year"
                                :options="yearOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                    </b-col>
                    <b-col sm>
                        <p>
                            <b-form-checkbox v-model="includeAddress">
                                {{ $t("Include address of donor") }}
                            </b-form-checkbox>
                        </p>
                    </b-col>
                </b-row>

                <template #modal-footer="{ ok, cancel }">
                    <template v-if="isBusy">
                        <b-spinner class="align-middle mr-2"></b-spinner>
                        {{ $t("Generating file...") }}
                    </template>
                    <template v-else>
                        <b-button variant="secondary" @click="cancel()">
                            {{ $t("Cancel") }}
                        </b-button>
                        <b-button variant="primary" @click="ok()">
                            {{ $t("Export") }}
                        </b-button>
                    </template>
                </template>
            </b-modal>
        </form>
    </div>
</template>

<script>
import donationsApi from "@/api/fundraising/donations";

export default {
    components: {
    },
    data() {
        return {
            title: this.$t('Export donations'),
            modalShow: false,
            isBusy: false,
            format: "xlsx",
            formats: {
                xlsx: this.$t("Excel (.xlsx)"),
            },
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
            return [
                {value: null, text: this.$t('Any')},
                ...this.years.map(e => ({
                    value: e,
                    text: e
                }))
            ];
        },
    },
    async created() {
        const res = await donationsApi.listYears();
        this.years = res.data
        this.years.sort((a,b)=>b-a);
    },
    methods: {
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handleSubmit();
        },
        async handleSubmit() {
            const params = {
                format: this.format,
                includeAddress: this.includeAddress,
                year: this.year
            };
            this.isBusy = true;
            try {
                const url = this.route("api.fundraising.donations.export", params);
                window.location.href = url
                this.modalShow = false;
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
