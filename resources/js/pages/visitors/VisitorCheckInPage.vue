<template>
    <b-container>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="display-4 mb-3">{{ $t("Visitor check-in") }}</h2>
            <span v-if="checkedInToday !== null"
                >{{ $t("{count} check-ins today", { count: checkedInToday }) }}
            </span>
        </div>

        <b-form-group>
            <b-form-input
                v-model.trim="search"
                type="search"
                debounce="500"
                :placeholder="
                    $t('Search visitor name, ID number or date of birth…')
                "
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
                        <VisitorCheckinButton
                            :value="visitor"
                            @checkin="checkedInToday = $event"
                        >
                            <template #append>
                                <b-button
                                    variant="secondary"
                                    :to="{
                                        name: 'visitors.edit',
                                        params: { id: visitor.id },
                                    }"
                                    ><font-awesome-icon icon="edit" />
                                    {{ $t("Edit") }}</b-button
                                >
                            </template>
                        </VisitorCheckinButton>
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

            <b-card
                v-if="showRegistrationForm"
                class="mb-3"
                :header="$t('Register new visitor')"
            >
                <VisitorForm
                    :disabled="isBusy"
                    @submit="handleCreate"
                    @cancel="showRegistrationForm = false"
                />
            </b-card>
            <p v-else>
                <b-button
                    variant="primary"
                    @click="showRegistrationForm = true"
                >
                    <font-awesome-icon icon="plus-circle" />
                    {{ $t("Register new visitor") }}</b-button
                >
            </p>
        </template>
        <p v-else class="text-muted text-center">
            <small>
                {{
                    $t(
                        "Inactive visitor records will be anonymized after {days} days.",
                        { days: settings["visitors.retention_days"] }
                    )
                }}
            </small>
        </p>
    </b-container>
</template>

<script>
import visitorsApi from "@/api/visitors";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import TablePagination from "@/components/table/TablePagination";
import VisitorDetails from "@/components/visitors/VisitorDetails";
import VisitorForm from "@/components/visitors/VisitorForm";
import VisitorCheckinButton from "@/components/visitors/VisitorCheckinButton";
import { showSnackbar } from "@/utils";
import { mapState } from "vuex";
export default {
    components: {
        AlertWithRetry,
        TablePagination,
        VisitorDetails,
        VisitorForm,
        VisitorCheckinButton,
    },
    data() {
        return {
            search:
                this.$route.query.search ??
                sessionStorage.getItem("visitors.checkin.filter") ??
                "",
            visitors: [],
            searched: false,
            isBusy: false,
            currentPage: 1,
            perPage: 10,
            totalRows: 0,
            errorText: null,
            checkedInToday: null,
            timer: null,
            showRegistrationForm: false,
        };
    },
    computed: {
        ...mapState(["settings"]),
    },
    watch: {
        search: {
            immediate: true,
            handler(value) {
                this.showRegistrationForm = false;
                this.searched = false;
                if (value.length > 0) {
                    this.$router.replace({ query: { search: value } });
                    this.searchVisitors();
                    sessionStorage.setItem(
                        "visitors.checkin.filter",
                        this.search
                    );
                } else {
                    this.$router.replace({ query: { search: undefined } });
                    this.visitors = [];
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
        async handleCreate(formData) {
            this.isBusy = true;
            try {
                let data = await visitorsApi.store(formData);
                this.visitors.push(data.data);
                showSnackbar(this.$t("Visitor registered."));
                this.showRegistrationForm = false;
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
    },
};
</script>
