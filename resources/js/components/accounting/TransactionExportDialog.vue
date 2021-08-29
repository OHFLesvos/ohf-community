<template>
    <div>
        <b-button variant="secondary" @click="modalShow = !modalShow">
            <font-awesome-icon icon="download" />
            {{ $t("Export") }}
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
                <b-form-group :label="$t('Grouping')" class="mb-3">
                    <b-form-radio-group
                        v-model="grouping"
                        :options="groupingOptions"
                        required
                        stacked
                    />
                </b-form-group>
                <b-form-group
                    v-if="hasFilter"
                    :label="$t('Selection')"
                    class="mb-3"
                >
                    <b-form-radio-group
                        v-model="selection"
                        :options="selectionOptions"
                        required
                        stacked
                    />
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
import transactionsApi from "@/api/accounting/transactions";
export default {
    props: {
        wallet: {
            required: true
        },
        filter: {
            required: false,
            type: String
        },
        advancedFilter: {
            required: false,
            type: Object
        }
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
            grouping: "none",
            groupings: {
                none: this.$t("None"),
                monthly: this.$t("Monthly")
            },
            selection: "all",
            selections: {
                all: this.$t("All records"),
                filtered: this.$t(
                    "Selected records according to current filter"
                )
            }
        };
    },
    computed: {
        hasFilter() {
            return (
                (this.filter && this.filter.length > 0) ||
                (this.advancedFilter &&
                    Object.keys(this.advancedFilter).length > 0)
            );
        },
        formatOptions() {
            return Object.entries(this.formats).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        groupingOptions() {
            return Object.entries(this.groupings).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        selectionOptions() {
            return Object.entries(this.selections).map(e => ({
                value: e[0],
                text: e[1]
            }));
        }
    },
    methods: {
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handleSubmit();
        },
        async handleSubmit() {
            const params = {
                wallet: this.wallet,
                format: this.format,
                grouping: this.grouping
            };
            if (this.selection == "filtered") {
                if (this.filter && this.filter.length > 0) {
                    params["filter"] = this.filter;
                }
                if (
                    this.advancedFilter &&
                    Object.keys(this.advancedFilter).length > 0
                ) {
                    Object.entries(this.advancedFilter).forEach(function([
                        key,
                        value
                    ]) {
                        if (key == "date_start") {
                            params["date_start"] = value;
                        } else if (key == "date_end") {
                            params["date_end"] = value;
                        } else {
                            params[`advanced_filter[${key}]`] = value;
                        }
                    });
                }
            }

            this.isBusy = true;
            try {
                await transactionsApi.export(params);
                this.modalShow = false;
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
