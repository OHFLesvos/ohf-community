<template>
    <div>
        <alert-with-retry :value="errorText" @retry="fetchData" />

        <div class="row">
            <div class="col-sm">
                <h4 class="mb-4">{{ heading }}</h4>
            </div>
            <div class="col-sm-8 col-md-9 col-lg-10 col-xl-auto">
                <div class="form-row">
                    <div class="col-auto mb-2">
                        <b-select
                            v-model="month"
                            :options="monthOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div class="col-auto mb-2">
                        <b-select
                            v-model="year"
                            :options="yearOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div class="col-auto mb-2">
                        <b-select
                            v-model="wallet"
                            :options="walletOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div v-if="projects.length > 0" class="col-auto mb-2">
                        <b-select
                            v-model="project"
                            :options="projectOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div v-if="locations.length > 0" class="col-auto mb-2">
                        <b-select
                            v-model="location"
                            :options="locationOptions"
                            :disabled="isBusy"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallets -->
        <div v-if="!isBusy" class="card shadow-sm mb-4 table-responsive">
            <table class="table table-hover mb-0">
                <thead class="card-header">
                    <th>{{ $t("Wallet") }}</th>
                    <th class="text-right">{{ $t("Income") }}</th>
                    <th class="text-right">{{ $t("Spending") }}</th>
                    <th class="text-right">{{ $t("Fees") }}</th>
                    <th class="text-right">{{ $t("Difference") }}</th>
                    <th class="text-right">{{ $t("Balance") }}</th>
                </thead>
                <tbody>
                    <tr
                        v-for="wallet in wallet
                            ? wallets.filter(w => w.id == wallet)
                            : wallets"
                        :key="wallet.id"
                    >
                        <td>
                            <a
                                v-if="can('can-view-transactions')"
                                :href="
                                    route('accounting.transactions.index', {
                                        wallet
                                    })
                                "
                            >
                                {{ wallet.name }}
                            </a>
                        </td>
                        <td class="text-right">
                            {{ numberFormat(wallet.income) }}
                        </td>
                        <td class="text-right">
                            {{ numberFormat(wallet.spending) }}
                        </td>
                        <td class="text-right">
                            {{ numberFormat(wallet.fees) }}
                        </td>
                        <td
                            class="text-right"
                            :class="
                                wallet.income > wallet.spending
                                    ? 'text-success'
                                    : 'text-danger'
                            "
                        >
                            {{ numberFormat(wallet.income - wallet.spending) }}
                        </td>
                        <td
                            class="text-right"
                            :class="
                                wallet.amount > 0
                                    ? 'text-success'
                                    : 'text-danger'
                            "
                        >
                            {{ numberFormat(wallet.amount) }}
                        </td>
                    </tr>
                    <tr v-if="!wallet">
                        <td>
                            <b>{{ $t("Sum across all wallets") }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ numberFormat(totals.income) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ numberFormat(totals.spending) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ numberFormat(totals.fees) }}</b>
                        </td>
                        <td
                            class="text-right"
                            :class="
                                totals.income > totals.spending
                                    ? 'text-success'
                                    : 'text-danger'
                            "
                        >
                            <b>{{
                                numberFormat(totals.income - totals.spending)
                            }}</b>
                        </td>
                        <td
                            class="text-right"
                            :class="
                                totals.amount > 0
                                    ? 'text-success'
                                    : 'text-danger'
                            "
                        >
                            <b>{{ numberFormat(totals.amount) }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!isBusy" class="row">
            <!-- Revenue by categories -->
            <div class="col-md">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">{{ $t("Categories") }}</div>
                    <table class="table table-strsiped mb-0">
                        <tbody>
                            <template v-if="revenueByCategory.length > 0">
                                <tr v-for="v in revenueByCategory" :key="v.id">
                                    <td>
                                        <a
                                            v-if="
                                                wallet &&
                                                    can('can-view-transactions')
                                            "
                                            :href="
                                                route(
                                                    'accounting.transactions.index',
                                                    {
                                                        wallet,
                                                        'filter[category_id]':
                                                            v.id,
                                                        'filter[date_start]': filterDateStart,
                                                        'filter[date_end]': filterDateEnd
                                                    }
                                                )
                                            "
                                        >
                                            {{ v.name }}
                                        </a>
                                        <template v-else>
                                            {{ v.name }}
                                        </template>
                                    </td>
                                    <td
                                        class="text-right"
                                        :class="
                                            v.amount > 0
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    >
                                        {{ numberFormat(v.amount) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td>
                                    <em>{{
                                        $t(
                                            "No data available in the selected time range."
                                        )
                                    }}</em>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue by secondary category -->
            <div v-if="revenueBySecondaryCategory" class="col-md">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        {{ $t("Secondary Categories") }}
                    </div>
                    <table class="table mb-0">
                        <tbody>
                            <template
                                v-if="revenueBySecondaryCategory.length > 0"
                            >
                                <tr
                                    v-for="v in revenueBySecondaryCategory"
                                    :key="v.name"
                                >
                                    <td>
                                        <template v-if="v.name">
                                            <a
                                                v-if="
                                                    wallet &&
                                                        can(
                                                            'can-view-transactions'
                                                        )
                                                "
                                                :href="
                                                    route(
                                                        'accounting.transactions.index',
                                                        {
                                                            wallet,
                                                            'filter[secondary_category]':
                                                                v.name,
                                                            'filter[date_start]': filterDateStart,
                                                            'filter[date_end]': filterDateEnd
                                                        }
                                                    )
                                                "
                                            >
                                                {{ v.name }}
                                            </a>
                                            <template v-else>
                                                {{ v.name }}
                                            </template>
                                        </template>
                                        <em v-else>{{
                                            $t("No Secondary Category")
                                        }}</em>
                                    </td>
                                    <td
                                        class="text-right"
                                        :class="
                                            v.amount > 0
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    >
                                        {{ numberFormat(v.amount) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td>
                                    <em>{{
                                        $t(
                                            "No data available in the selected time range."
                                        )
                                    }}</em>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue by project -->
            <div class="col-md">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">{{ $t("Projects") }}</div>
                    <table class="table table-strsiped mb-0">
                        <tbody>
                            <template v-if="revenueByProject.length > 0">
                                <tr v-for="v in revenueByProject" :key="v.id">
                                    <td>
                                        <template v-if="v.name">
                                            <a
                                                v-if="
                                                    wallet &&
                                                        can(
                                                            'can-view-transactions'
                                                        )
                                                "
                                                :href="
                                                    route(
                                                        'accounting.transactions.index',
                                                        {
                                                            wallet,
                                                            'filter[project_id]':
                                                                v.id,
                                                            'filter[date_start]': filterDateStart,
                                                            'filter[date_end]': filterDateEnd
                                                        }
                                                    )
                                                "
                                            >
                                                {{ v.name }}
                                            </a>
                                            <template v-else>
                                                {{ v.name }}
                                            </template>
                                        </template>

                                        <em v-else>{{ $t("No project") }}</em>
                                    </td>
                                    <td
                                        class="text-right"
                                        :class="
                                            v.amount > 0
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    >
                                        {{ numberFormat(v.amount) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td>
                                    <em>{{
                                        $t(
                                            "No data available in the selected time range."
                                        )
                                    }}</em>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import summaryApi from "@/api/accounting/summary";
import numeral from "numeral";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import { can } from "@/plugins/laravel";
export default {
    components: {
        AlertWithRetry
    },
    data() {
        const now = moment();
        return {
            years: [now.year()],
            wallets: [],
            totals: {},
            projects: [],
            locations: [],

            year: this.$route.query.year ?? now.year(),
            month: this.$route.query.month
                ? this.$route.query.month - 1
                : now.month(),
            wallet: this.$route.query.wallet ?? null,
            project: this.$route.query.project ?? null,
            location: this.$route.query.location ?? null,

            revenueByCategory: [],
            revenueBySecondaryCategory: [],
            revenueByProject: [],

            isBusy: false,
            errorText: null
        };
    },
    computed: {
        heading() {
            let str = "";
            if (this.year != null && this.month != null) {
                const date = moment(
                    `${this.year}-${this.month + 1}`,
                    "YYYY-M"
                ).startOf("month");
                str += date.format("MMMM YYYY");
            } else if (this.year != null) {
                str += this.year;
            } else {
                str += this.$t("All time");
            }
            return str;
        },
        monthOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Month")} -`
                }
            ];
            arr.push(
                ...moment.months().map((month, idx) => ({
                    value: idx,
                    text: month
                }))
            );
            return arr;
        },
        yearOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("Year")} -`
                }
            ];
            arr.push(
                ...this.years.map(year => ({
                    value: year,
                    text: year
                }))
            );
            return arr;
        },
        walletOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("All wallets")} -`
                }
            ];
            arr.push(
                ...this.wallets.map(e => ({
                    value: e.id,
                    text: e.name
                }))
            );
            return arr;
        },
        projectOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("All projects")} -`
                }
            ];
            arr.push(
                ...this.projects.map(e => ({
                    value: e.id,
                    text: e.label.replaceAll("&nbsp;", "-")
                }))
            );
            return arr;
        },
        locationOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("All locations")} -`
                }
            ];
            arr.push(
                ...this.locations.map(e => ({
                    value: e,
                    text: e
                }))
            );
            return arr;
        },
        filterDateStart() {
            if (this.year != null && this.month != null) {
                return moment(`${this.year}-${this.month + 1}`, "YYYY-M")
                    .startOf("month")
                    .format(moment.HTML5_FMT.DATE);
            } else if (this.year != null) {
                return moment(`${this.year}`, "YYYY")
                    .startOf("year")
                    .format(moment.HTML5_FMT.DATE);
            }
            return null;
        },
        filterDateEnd() {
            if (this.year != null && this.month != null) {
                return moment(`${this.year}-${this.month + 1}`, "YYYY-M")
                    .endOf("month")
                    .format(moment.HTML5_FMT.DATE);
            } else if (this.year != null) {
                return moment(`${this.year}`, "YYYY")
                    .endOf("year")
                    .format(moment.HTML5_FMT.DATE);
            }
            return null;
        }
    },
    watch: {
        month(val) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    month: val !== null ? val + 1 : undefined
                }
            });
            this.fetchData();
        },
        year(val) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    year: val || undefined
                }
            });
            this.fetchData();
        },
        wallet(val) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    wallet: val || undefined
                }
            });
            this.fetchData();
        },
        project(val) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    project: val || undefined
                }
            });
            this.fetchData();
        },
        location(val) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    location: val || undefined
                }
            });
            this.fetchData();
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        can,
        async fetchData() {
            this.errorText = null;
            this.isBusy = true;
            try {
                let data = await summaryApi.list({
                    year: this.year,
                    month: this.month !== null ? this.month + 1 : undefined,
                    wallet: this.wallet,
                    project: this.project,
                    location: this.location
                });

                this.years = data.years;
                this.wallets = data.wallets;
                this.totals = data.totals;
                this.projects = data.projects;
                this.locations = data.locations;

                this.revenueByCategory = data.revenueByCategory;
                this.revenueBySecondaryCategory =
                    data.revenueBySecondaryCategory;
                this.revenueByProject = data.revenueByProject;

                this.isBusy = false;
            } catch (err) {
                this.errorText = err;
            }
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
        }
    }
};
</script>
