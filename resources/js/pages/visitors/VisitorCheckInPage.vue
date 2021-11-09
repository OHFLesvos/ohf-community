<template>
    <b-container>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="display-4 mb-3">{{ $t("Visitor check-in") }}</h2>
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
                <b-card
                    v-for="visitor in visitors"
                    :key="visitor.id"
                    class="mb-3 shadow-sm"
                >
                    <VisitorDetails :value="visitor" />
                    <template #footer>
                        <b-button
                            :variant="
                                visitor.checked_in_today
                                    ? 'secondary'
                                    : 'primary'
                            "
                            :disabled="visitor.checked_in_today || isBusy"
                            @click="checkin(visitor)"
                            >Check-in</b-button
                        >
                    </template>
                </b-card>
                <table-pagination
                    v-if="totalRows > perPage"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    :disabled="isBusy"
                />
            </template>
            <template v-else>
                <b-alert variant="warning" show>{{
                    $t("No results.")
                }}</b-alert>
            </template>

            <p>
                <b-button
                    variant="primary"
                    :to="{
                        name: 'visitors.create',
                        query: { search: this.search },
                    }"
                    >{{ $t("Register new visitor") }}</b-button
                >
            </p>
        </template>
    </b-container>
</template>

<script>
import visitorsApi from "@/api/visitors";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import TablePagination from "@/components/table/TablePagination";
import VisitorDetails from "@/components/visitors/VisitorDetails";
import { showSnackbar } from "@/utils";
export default {
    components: {
        AlertWithRetry,
        TablePagination,
        VisitorDetails,
    },
    data() {
        return {
            search: sessionStorage.getItem("visitors.checkin.filter") ?? "",
            visitors: [],
            searched: false,
            isBusy: false,
            currentPage: 1,
            perPage: 10,
            totalRows: 0,
            errorText: null,
            checkedInToday: null,
            timer: null,
        };
    },
    watch: {
        search: {
            immediate: true,
            handler(value) {
                if (value.length > 0) {
                    this.searchVisitors();
                    sessionStorage.setItem(
                        "visitors.checkin.filter",
                        this.search
                    );
                } else {
                    this.visitors = [];
                    this.searched = false;
                    sessionStorage.removeItem("visitors.checkin.filter");
                }
            },
        },
        currentPage() {
            this.searchVisitors();
        },
    },
    async created() {
        this.fetchCheckins();
        this.timer = setInterval(this.fetchCheckins, 60 * 1000);
    },
    beforeRouteLeave(to, from, next) {
        if (this.timer) {
            clearInterval(this.timer);
        }
        next();
    },
    methods: {
        async fetchCheckins() {
            try {
                const data = await visitorsApi.checkins();
                this.checkedInToday = data.checked_in_today;
            } catch (ex) {
                console.error(ex);
            }
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
                let data = await visitorsApi.checkin(visitor.id);
                visitor.checked_in_today = true;
                this.checkedInToday = data.checked_in_today;
                showSnackbar(`Checked in ${visitor.name}.`);
            } catch (ex) {
                alert(ex);
            }
            this.isBusy = false;
        },
    },
};
</script>
