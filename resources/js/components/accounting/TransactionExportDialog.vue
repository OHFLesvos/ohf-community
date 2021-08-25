<template>
    <div>
        <b-button variant="secondary" @click="modalShow = !modalShow">
            <font-awesome-icon icon="download" />
            {{ $t("Export") }}
        </b-button>
        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-modal
                v-model="modalShow"
                id="modal-export-transactions"
                :title="$t('Export')"
                footer-class="d-flex justify-content-between"
                :cancel-title="$t('Cancel')"
                :ok-title="$t('Export')"
                :ok-disabled="isBusy"
                @ok="handleOk"
            >
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
            </b-modal>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        wallet: {
            required: true,
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
            }
        };
    },
    computed: {
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
        }
    },
    methods: {
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.handleSubmit();
        },
        handleSubmit() {
            this.isBusy = true;
            document.location = this.route(
                "api.accounting.transactions.export",
                {
                    wallet: this.wallet,
                    format: this.format,
                    grouping: this.grouping
                }
            );
            setTimeout(() => {
                this.$nextTick(() => {
                    this.$bvModal.hide("modal-export-transactions");
                });
            }, 1000);
        }
    }
};
</script>
