<template>
    <div>
        <alert-with-retry :value="errorText" @retry="fetchData" />

        <div class="row">
            <div class="col-sm">
                <h4 class="mb-4">{{ heading }}</h4>
            </div>
            <div class="col-xl-auto col-md">
                <div class="form-row">
                    <div class="col-xl col-sm-6">
                        <b-select
                            v-model="month"
                            :options="monthOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div class="col-xl col-sm-6">
                        <b-select
                            v-model="year"
                            :options="yearOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div class="col-xl col-sm-6">
                        <b-select
                            v-model="wallet"
                            :options="walletOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div v-if="projects.length > 0" class="col-xl col-sm-6">
                        <b-select
                            v-model="project"
                            :options="projectOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div v-if="locations.length > 0" class="col-xl col-sm-6">
                        <b-select
                            v-model="location"
                            :options="locationOptions"
                            :disabled="isBusy"
                        />
                    </div>
                </div>
            </div>
        </div>
#{{ wallet }}
        <!-- Wallets -->
        <div class="card shadow-sm mb-4 table-responsive">
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
                    <tr v-if="wallet">
                        <td></td>
                        <td class="text-right">
                            <u>{{ numberFormat(income) }}</u>
                        </td>
                        <td class="text-right">
                            <u>{{ numberFormat(spending) }}</u>
                        </td>
                        <td class="text-right">
                            <u>{{ numberFormat(fees) }}</u>
                        </td>
                        <td class="text-right">
                            <u>{{ numberFormat(income - spending) }}</u>
                        </td>
                        <td
                            class="text-right"
                            :class="wallet_amount < 0 ? 'text-danger' : ''"
                        >
                            <u>{{ numberFormat(wallet_amount) }}</u>
                        </td>
                    </tr>
                    <template v-else>
                        <tr v-for="wallet in wallets" :key="wallet.id">
                            <td>
                                {{ wallet.name }}

                                #{{ wallet.id }}
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
                                {{
                                    numberFormat(
                                        wallet.income - wallet.spending
                                    )
                                }}
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
                        <tr>
                            <td>
                                <b>{{ $t("Sum across all wallets") }}</b>
                            </td>
                            <td class="text-right">
                                <b>{{ numberFormat(income) }}</b>
                            </td>
                            <td class="text-right">
                                <b>{{ numberFormat(spending) }}</b>
                            </td>
                            <td class="text-right">
                                <b>{{ numberFormat(fees) }}</b>
                            </td>
                            <td
                                class="text-right"
                                :class="
                                    income > spending
                                        ? 'text-success'
                                        : 'text-danger'
                                "
                            >
                                <b>{{ numberFormat(income - spending) }}</b>
                            </td>
                            <td
                                class="text-right"
                                :class="
                                    wallet_amount > 0
                                        ? 'text-success'
                                        : 'text-danger'
                                "
                            >
                                <b>{{ numberFormat(wallet_amount) }}</b>
                            </td>
                        </tr>
                    </template>
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
                                        <!-- @if(Auth::user()->can('viewAny', App\Models\Accounting\MoneyTransaction::class)) -->
                                        <a
                                            v-if="wallet != null"
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
                                            <!-- @if(Auth::user()->can('viewAny', App\Models\Accounting\MoneyTransaction::class)) -->
                                            <a
                                                v-if="wallet != null"
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
                                            <!-- @if(Auth::user()->can('viewAny', App\Models\Accounting\MoneyTransaction::class)) -->
                                            <a
                                                v-if="wallet != null"
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
export default {
    components: {
        AlertWithRetry
    },
    data() {
        const now = moment();
        return {
            years: [now.year()],
            wallets: [],
            projects: [],
            locations: [],

            year: now.year(),
            month: now.month(),
            wallet: null,
            project: null,
            location: null,

            income: 0,
            spending: 0,
            fees: 0,

            wallet_amount: 0,
            revenueByCategory: [],
            revenueBySecondaryCategory: [],
            revenueByProject: [],

            isBusy: false,
            errorText: false
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
            str += " - ";
            if (this.wallet != null) {
                str += this.wallets[this.wallet]?.name;
            } else {
                str += this.$t("All wallets");
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
                    text: e.label
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
            this.fetchData();
        },
        year(val) {
            this.fetchData();
        },
        wallet(val) {
            this.fetchData();
        },
        project(val) {
            this.fetchData();
        },
        location(val) {
            this.fetchData();
        }
    },
    mounted() {
        // console.log(this.$route.query.wallet);
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.errorText = null;
            this.isBusy = true;
            try {
                let data = await summaryApi.list({
                    year: this.year,
                    month: this.month + 1,
                    wallet: this.wallet,
                    project: this.project,
                    location: this.location
                });

                this.years = data.years;
                this.wallets = data.wallets;
                this.projects = data.projects;
                this.locations = data.locations;

                this.income = data.income;
                this.spending = data.spending;
                this.fees = data.fees;
                this.wallet_amount = data.wallet_amount;

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
