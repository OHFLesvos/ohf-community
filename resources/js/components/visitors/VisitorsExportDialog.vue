<template>
    <div class="d-inline">
        <b-button variant="secondary" @click="modalShow = !modalShow">
            <font-awesome-icon icon="download" />
            <span class="d-none d-sm-inline">{{ $t("Export") }}</span>
        </b-button>
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-modal v-model="modalShow" :title="$t('Export')" @ok="handleOk">
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
                <b-form-group v-if="type == 'checkins'" :label="$t('Date range')">
                    <DateRangeSelect v-model="dateRange" noGranularity noReset :prepend="null"/>
                </b-form-group>

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
import visitorsApi from "@/api/visitors";
import moment from "moment";
import DateRangeSelect from "@/components/common/DateRangeSelect.vue";
export default {
    components: {
        DateRangeSelect,
    },
    data() {
        return {
            modalShow: false,
            isBusy: false,
            format: "xlsx",
            formats: {
                xlsx: this.$t("Excel (.xlsx)"),
                csv: this.$t("Comma-separated values (.csv)"),
                tsv: this.$t("Tab-separated values (.tsv)"),
                pdf: this.$t("PDF (.pdf)")
            },
            type: "visitors",
            types: {
                visitors: this.$t("Visitors"),
                checkins: this.$t("Check-ins")
            },
            dateRange: {
                from: moment()
                    .startOf('month')
                    .format(moment.HTML5_FMT.DATE),
                to: moment().format(moment.HTML5_FMT.DATE),
            },
        };
    },
    computed: {
        formatOptions() {
            return Object.entries(this.formats).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        typeOptions() {
            return Object.entries(this.types).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        today() {
            return moment().format(moment.HTML5_FMT.DATE);
        },
    },
    methods: {
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handleSubmit();
        },
        async handleSubmit() {
            const params = {
                format: this.format,
                type: this.type,
                date_from: this.dateRange.from,
                date_to: this.dateRange.to,
            };

            this.isBusy = true;
            try {
                await visitorsApi.export(params);
                this.modalShow = false;
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
