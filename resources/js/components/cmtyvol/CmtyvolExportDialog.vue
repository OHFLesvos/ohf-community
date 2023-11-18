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
                        <b-form-group :label="$t('Work status')" class="mb-3">
                            <b-form-radio-group
                                v-model="workStatus"
                                :options="workStatusOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                        <b-form-group :label="$t('Columns')" class="mb-3">
                            <b-form-radio-group
                                v-model="columnSet"
                                :options="columnSetOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                    </b-col>
                    <b-col sm>
                        <b-form-group :label="$t('Order')" class="mb-3">
                            <b-form-radio-group
                                v-model="sorting"
                                :options="sortingOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                        <b-form-group :label="$t('Orientation')" class="mb-3">
                            <b-form-radio-group
                                v-model="orientation"
                                :options="orientationOptions"
                                required
                                stacked
                            />
                        </b-form-group>
                        <p>
                            <b-form-checkbox v-model="fit_to_page">
                                {{ $t("Fit to page") }}
                            </b-form-checkbox>
                        </p>
                        <p>
                            <b-form-checkbox v-model="include_portraits">
                                {{ $t("Include Portraits") }}
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
import cmtyvolApi from "@/api/cmtyvol/cmtyvol";

export default {
    components: {
    },
    data() {
        return {
            title: this.$t('Export community volunteer data'),
            modalShow: false,
            isBusy: false,
            format: "xlsx",
            formats: {
                xlsx: this.$t("Excel (.xlsx)"),
                csv: this.$t("Comma-separated values (.csv)"),
                tsv: this.$t("Tab-separated values (.tsv)"),
                pdf: this.$t("PDF (.pdf)")
            },
            workStatuses: {
                active: this.$t('Active'),
                future: this.$t('Future'),
                alumni: this.$t('Alumni'),
            },
            workStatus: 'active',
            columnSets: {
                all: this.$t('All'),
                name_nationality_occupation: this.$t('Nationality, Occupation'),
                contact_info: this.$t('Contact information'),
                name_nationality_comments: this.$t('Comments'),
            },
            columnSet: 'all',
            sorters: {
                first_name: this.$t('First Name'),
                last_name: this.$t('Last Name'),
                nationality: this.$t('Nationality'),
                gender: this.$t('Gender'),
                age: this.$t('Age'),
                residence: this.$t('Residence'),
                pickup_location: this.$t('Pickup location'),
            },
            sorting: 'first_name',
            orientations: {
                portrait: this.$t('Portrait'),
                landscape: this.$t('Landscape'),
            },
            orientation: 'portrait',
            fit_to_page: false,
            include_portraits: false,
        };
    },
    computed: {
        formatOptions() {
            return Object.entries(this.formats).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        workStatusOptions() {
            return Object.entries(this.workStatuses).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        columnSetOptions() {
            return Object.entries(this.columnSets).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        sortingOptions() {
            return Object.entries(this.sorters).map(e => ({
                value: e[0],
                text: e[1]
            }));
        },
        orientationOptions() {
            return Object.entries(this.orientations).map(e => ({
                value: e[0],
                text: e[1]
            }));
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
                work_status: this.workStatus,
                column_set: this.columnSet,
                sorting: this.sorting,
                orientation: this.orientation,
                fit_to_page: this.fit_to_page,
                include_portraits: this.include_portraits,
            };
            this.isBusy = true;
            try {
                await cmtyvolApi.export(params);
                this.modalShow = false;
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        }
    }
};
</script>
