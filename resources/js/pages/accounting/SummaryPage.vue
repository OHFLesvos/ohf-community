<template>
    <div>
        <div class="row">
            <div class="col-sm">
                <h4 class="mb-4">{{ heading }}</h4>
            </div>
            <div class="col-xl-auto col-md">
                <div class="row">
                    <div
                        v-if="monthsOptions.length > 0"
                        class="col-xl col-sm-6"
                    >
                        <b-select
                            v-model="monthrange"
                            :options="monthsOptions"
                        />
                    </div>
                    <div v-if="yearsOptions.length > 0" class="col-xl col-sm-6">
                        <b-select v-model="yearrange" :options="yearsOptions" />
                    </div>
                    <div
                        v-if="projectsOptions.length > 0"
                        class="col-xl col-sm-6"
                    >
                        <b-select
                            v-model="project"
                            :options="projectsOptions"
                        />
                    </div>
                    <div
                        v-if="locationsOptions.length > 0"
                        class="col-xl col-sm-6"
                    >
                        <b-select
                            v-model="location"
                            :options="locationsOptions"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Summary by wallet -->
            <div v-if="wallets.length > 0" class="col-md-12 col-xl-6">
                <div class="card shadow-sm mb-4 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="card-header">
                            <th></th>
                            <th class="text-right">{{ $t("Income") }}</th>
                            <th class="text-right">{{ $t("Spending") }}</th>
                            <th class="text-right">{{ $t("Fees") }}</th>
                            <th class="text-right">{{ $t("Difference") }}</th>
                            <th class="text-right">{{ $t("Wallet") }}</th>
                        </thead>
                        <tbody>
                            <tr v-for="w in wallets" :key="w.wallet.id">
                                <td>
                                    {{ w.wallet.name }}
                                </td>
                                <td class="text-right">
                                    {{ numberFormat(w.income) }}
                                </td>
                                <td class="text-right">
                                    {{ numberFormat(w.spending) }}
                                </td>
                                <td class="text-right">
                                    {{ numberFormat(w.fees) }}
                                </td>
                                <td
                                    class="text-right"
                                    :class="
                                        w.income > w.spending
                                            ? 'text-success'
                                            : 'text-danger'
                                    "
                                >
                                    {{ numberFormat(w.income - w.spending) }}
                                </td>
                                <td
                                    class="text-right"
                                    :class="
                                        w.amount > 0
                                            ? 'text-success'
                                            : 'text-danger'
                                    "
                                >
                                    {{ numberFormat(w.amount) }}
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
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue by categories -->
            <div class="col-sm-6 col-md">
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
            <div v-if="revenueBySecondaryCategory" class="col-sm-6 col-md">
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
            <div class="col-sm-6 col-md">
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

            <!-- Wallet -->
            <div v-if="wallet" class="col-sm-6 col-md">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">{{ $t("Total") }}</div>
                    <table class="table table-strsiped mb-0">
                        <tbody>
                            <tr>
                                <td>{{ $t("Income") }}</td>
                                <td class="text-right">
                                    <u>{{ numberFormat(income) }}</u>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Spending") }}</td>
                                <td class="text-right">
                                    <u>{{ numberFormat(spending) }}</u>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Transaction fees") }}</td>
                                <td class="text-right">
                                    <u>{{ numberFormat(fees) }}</u>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Difference") }}</td>
                                <td class="text-right">
                                    <u>{{ numberFormat(income - spending) }}</u>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ $t("Wallet") }}</td>
                                <td
                                    class="text-right"
                                    :class="
                                        wallet_amount < 0 ? 'text-danger' : ''
                                    "
                                >
                                    <u>{{ numberFormat(wallet_amount) }}</u>
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
import summaryApi from "@/api/accounting/summary";
import numeral from "numeral";
export default {
    data() {
        return {
            heading: null,
            months: {},
            years: {},
            projects: {},
            locations: [],
            monthrange: null,
            yearrange: null,
            project: null,
            location: null,
            wallets: [],
            income: 0,
            spending: 0,
            fees: 0,
            wallet_amount: 0,
            revenueByCategory: [],
            revenueBySecondaryCategory: [],
            revenueByProject: [],
            wallet: null,
            filterDateStart: null,
            filterDateEnd: null
        };
    },
    computed: {
        monthsOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("by month")} -`
                }
            ];
            arr.push(
                ...Object.entries(this.months).map(e => ({
                    value: e[0],
                    text: e[1]
                }))
            );
            return arr;
        },
        yearsOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("by year")} -`
                }
            ];
            arr.push(
                ...Object.entries(this.years).map(e => ({
                    value: e[0],
                    text: e[1]
                }))
            );
            return arr;
        },
        projectsOptions() {
            let arr = [
                {
                    value: null,
                    text: `- ${this.$t("All projects")} -`
                }
            ];
            arr.push(
                ...Object.entries(this.projects).map(e => ({
                    value: e[0],
                    text: e[1]
                }))
            );
            return arr;
        },
        locationsOptions() {
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
        }
    },
    watch: {
        monthrange(val) {
            let month = ''
            let year = ''
            if (val != '') {
                var arr = val.split('-');
                month = parseInt(arr[1]);
                year = arr[0]
            }
            // document.location = '{{ route('accounting.transactions.summary', ['wallet' => $wallet]) }}?month=' + month + '&year=' + year;
        },
        yearrange(val) {
            // document.location = '{{ route('accounting.transactions.summary', ['wallet' => $wallet]) }}?year=' + val;
        },
        project(val) {
            // document.location = '{{ route('accounting.transactions.summary', ['wallet' => $wallet]) }}?project=' + val;
        },
        location(val) {
            // document.location = '{{ route('accounting.transactions.summary', ['wallet' => $wallet]) }}?location=' + val;
        }
    },
    mounted() {
        // console.log(this.$route.query.wallet);
        this.fetchData();
    },
    methods: {
        async fetchData() {
            let data = await summaryApi.list();
            this.heading = data.heading + ' - ' + (data.wallet != null ? data.wallet.name : this.$t('All wallets'))
            this.months = data.months;
            this.years = data.years;
            this.projects = data.projects;
            this.locations = data.locations;
            this.monthrange = data.currentRange;
            this.yearrange = data.currentRange;
            this.project = data.currentProject;
            this.location = data.currentLocation;
            this.wallets = data.wallets ?? [];
            this.income = data.income;
            this.spending = data.spending;
            this.fees = data.fees;
            this.wallet_amount = data.wallet_amount;
            this.wallet = data.wallet;
            this.revenueByCategory = data.revenueByCategory;
            this.revenueBySecondaryCategory = data.revenueBySecondaryCategory;
            this.revenueByProject = data.revenueByProject;
            this.filterDateStart = data.filterDateStart;
            this.filterDateEnd = data.filterDateEnd;
        },
        numberFormat(val) {
            return numeral(val).format("0,0.00");
        }
    }
};
</script>
