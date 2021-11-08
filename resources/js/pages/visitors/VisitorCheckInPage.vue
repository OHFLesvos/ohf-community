<template>
    <b-container>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="display-4 mb-3">Visitor check-in</h2>
            <span v-if="checkedInToday !== null"
                >{{ checkedInToday }} check-ins today</span
            >
        </div>

        <b-form-group>
            <b-form-input
                v-model.trim="search"
                type="search"
                debounce="500"
                placeholder="Search visitor name, ID number or date of birth…"
                autofocus
                autocomplete="off"
            />
        </b-form-group>

        <alert-with-retry :value="errorText" @retry="searchVisitors()" />

        <template v-if="searched">
            <template v-if="visitors.length > 0">
                <b-list-group class="mb-3">
                    <b-list-group-item
                        v-for="visitor in visitors"
                        :key="visitor.id"
                    >
                        <b-row class="align-items-center">
                            <b-col>
                                <dl class="row mb-0">
                                    <template v-if="visitor.name">
                                        <dt class="col-sm-4">
                                            {{ $t("Name") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.name }}
                                        </dd>
                                    </template>
                                    <template v-if="visitor.id_number">
                                        <dt class="col-sm-4">
                                            {{ $t("ID Number") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.id_number }}
                                        </dd>
                                    </template>
                                    <template v-if="visitor.gender">
                                        <dt class="col-sm-4">
                                            {{ $t("Gender") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.gender }}
                                        </dd>
                                    </template>
                                    <template v-if="visitor.nationality">
                                        <dt class="col-sm-4">
                                            {{ $t("Nationality") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.nationality }}
                                        </dd>
                                    </template>
                                    <template v-if="visitor.date_of_birth">
                                        <dt class="col-sm-4">
                                            {{ $t("Date of birth") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.date_of_birth }}
                                        </dd>
                                    </template>
                                    <template v-if="visitor.date_of_birth">
                                        <dt class="col-sm-4">
                                            {{ $t("Living situation") }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            {{ visitor.living_situation }}
                                        </dd>
                                    </template>
                                </dl>
                            </b-col>
                            <b-col cols="auto">
                                <b-button
                                    :variant="
                                        visitor.checked_in_today
                                            ? 'secondary'
                                            : 'primary'
                                    "
                                    :disabled="
                                        visitor.checked_in_today || isBusy
                                    "
                                    @click="checkin(visitor)"
                                    >Check-in</b-button
                                >
                            </b-col>
                        </b-row>
                    </b-list-group-item>
                </b-list-group>
                <table-pagination
                    v-if="totalRows > perPage"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    :disabled="isBusy"
                />
            </template>
            <template v-else>
                <b-alert variant="warning" show>No results.</b-alert>
            </template>
        </template>
    </b-container>
</template>

<script>
import visitorsApi from "@/api/visitors";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import TablePagination from "@/components/table/TablePagination";
import { showSnackbar } from "@/utils";
export default {
    components: {
        AlertWithRetry,
        TablePagination,
    },
    data() {
        return {
            search: "",
            visitors: [],
            searched: false,
            isBusy: false,
            currentPage: 1,
            perPage: 10,
            totalRows: 0,
            errorText: null,
            checkedInToday: null,
        };
    },
    watch: {
        search(val) {
            if (val.length > 0) {
                this.searchVisitors();
            } else {
                this.visitors = [];
                this.searched = false;
            }
        },
        currentPage() {
            this.searchVisitors();
        },
    },
    async created() {
        this.fetchCheckins();
    },
    methods: {
        async fetchCheckins() {
            const data = await visitorsApi.checkins();
            this.checkedInToday = data.checked_in_today;
        },
        async searchVisitors() {
            this.isBusy = true;
            this.errorText = null;
            try {
                let data = await visitorsApi.list({
                    filter: this.search,
                    page: this.currentPage,
                    pageSize: this.perPage,
                });
                this.visitors = data.data;
                this.totalRows = data.meta.total;
                this.searched = true;
            } catch (ex) {
                this.errorText = ex;
            }
            this.isBusy = false;
        },
        async checkin(visitor) {
            this.isBusy = true;
            try {
                await visitorsApi.checkin(visitor.id);
                visitor.checked_in_today = true;
                showSnackbar("Checked in " + visitor.name);
                this.fetchCheckins();
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        },
    },
};
</script>
