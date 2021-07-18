<template>
    <div>
        <h1 class="display-4">
            {{ $t("Summary") }}
            <small class="ml-2">{{ heading }}</small>
        </h1>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div class="row">
            <div class="col-sm">
                <!-- <div class="form-row">
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
                </div> -->
            </div>
            <div class="col-sm-8 col-md-9 col-lg-10 col-xl-auto">
                <div class="form-row">
                    <div class="col-auto mb-2">
                        <b-button
                            variant="secondary"
                            @click="setToCurrentMonth()"
                            ><font-awesome-icon icon="calendar-alt"
                        /></b-button>
                    </div>
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
                </div>
            </div>
        </div>

        <!-- Wallets -->
        <b-table
            hover
            responsive
            :items="wallets"
            :fields="walletFields"
            class="shadow-sm mb-4 bg-white"
        >
            <template #cell(name)="data">
                <a
                    v-if="can('can-view-transactions')"
                    :href="
                        route('accounting.transactions.index', {
                            wallet: data.item.id
                        })
                    "
                >
                    {{ data.value }}
                </a>
                <template v-else>
                    {{ data.value }}
                </template>
            </template>
            <template #custom-foot>
                <b-tr>
                    <b-td>
                        <strong>{{ $t("Sum across all wallets") }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ numberFormat(totals.income) }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ numberFormat(totals.spending) }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{
                            numberFormat(totals.income - totals.spending)
                        }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ numberFormat(totals.fees) }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ numberFormat(totals.amount) }}</strong>
                    </b-td>
                </b-tr>
            </template>
        </b-table>

        <!-- <b-tr v-if="!wallet">
                        <b-td>
                            <strong>{{ $t("Sum across all wallets") }}</strong>
                        </b-td>
                        <b-td class="text-right">
                            <strong>{{ numberFormat(totals.income) }}</strong>
                        </b-td>
                        <b-td class="text-right">
                            <strong>{{ numberFormat(totals.spending) }}</strong>
                        </b-td>
                        <b-td class="text-right">
                            <strong>{{ numberFormat(totals.fees) }}</strong>
                        </b-td>
                        <b-td
                            class="text-right"
                            :class="colorClass(totals.income > totals.spending)"
                        >
                            <strong>{{
                                numberFormat(totals.income - totals.spending)
                            }}</strong>
                        </b-td>
                        <b-td
                            class="text-right"
                            :class="colorClass(totals.amount > 0)"
                        >
                            <strong>{{ numberFormat(totals.amount) }}</strong>
                        </b-td>
                    </b-tr> -->

        <b-row v-if="!isBusy">
            <!-- Revenue by categories -->
            <b-col md>
                <SummaryList
                    :items="revenueByCategory"
                    :title="$t('Categories')"
                    paramName="category_id"
                    :wallet="wallet"
                    :filterDateStart="filterDateStart"
                    :filterDateEnd="filterDateEnd"
                />
            </b-col>

            <!-- Revenue by secondary category -->
            <!-- <b-col v-if="revenueBySecondaryCategory" md>
                <SummaryList
                    :items="revenueBySecondaryCategory"
                    :title="$t('Secondary Categories')"
                    paramName="secondary_category"
                    :noNameLabel="$t('No Secondary Category')"
                    :wallet="wallet"
                    :filterDateStart="filterDateStart"
                    :filterDateEnd="filterDateEnd"
                />
            </b-col> -->

            <!-- Revenue by project -->
            <b-col md>
                <SummaryList
                    :items="revenueByProject"
                    :title="$t('Projects')"
                    paramName="project_id"
                    :noNameLabel="$t('No project')"
                    :wallet="wallet"
                    :filterDateStart="filterDateStart"
                    :filterDateEnd="filterDateEnd"
                />
            </b-col>
        </b-row>
    </div>
</template>

<script>
import moment from "moment";
import summaryApi from "@/api/accounting/summary";
import numeral from "numeral";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import SummaryList from "@/components/accounting/SummaryList";
import { can } from "@/plugins/laravel";
export default {
    components: {
        AlertWithRetry,
        SummaryList
    },
    data() {
        return {
            years: [moment().year()],
            wallets: [],
            totals: {},
            projects: [],
            locations: [],

            year: this.$route.query.year ?? null,
            month: this.$route.query.month ? this.$route.query.month - 1 : null,
            wallet: this.$route.query.wallet ?? null,
            project: this.$route.query.project ?? null,
            location: this.$route.query.location ?? null,

            revenueByCategory: [],
            revenueBySecondaryCategory: [],
            revenueByProject: [],

            isBusy: false,
            errorText: null,

            walletFields: [
                {
                    key: "name",
                    label: this.$t("Wallet")
                },
                {
                    key: "income",
                    label: this.$t("Income"),
                    class: "text-right",
                    formatter: (value, key, item) => this.numberFormat(value)
                },
                {
                    key: "spending",
                    label: this.$t("Spending"),
                    class: "text-right",
                    formatter: (value, key, item) => this.numberFormat(value)
                },
                {
                    key: "difference",
                    label: this.$t("Difference"),
                    class: "text-right",
                    formatter: (value, key, item) =>
                        this.numberFormat(item.income - item.spending),
                    tdClass: (value, key, item) =>
                        this.colorClass(item.income - item.spending > 0)
                },
                {
                    key: "fees",
                    label: this.$t("Fees"),
                    class: "text-right",
                    formatter: (value, key, item) => this.numberFormat(value)
                },
                {
                    key: "amount",
                    label: this.$t("Balance"),
                    class: "text-right",
                    formatter: (value, key, item) => this.numberFormat(value),
                    tdClass: (value, key, item) => this.colorClass(value > 0)
                }
            ]
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
            for (let elem of this.projects) {
                this.fillTree(arr, elem);
            }
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
    created() {
        this.fetchData();
    },
    methods: {
        fillTree(tree, elem, level = 0) {
            let text = "";
            if (level > 0) {
                text += "&nbsp;".repeat(level * 5);
            }
            text += elem.name;
            tree.push({
                html: text,
                value: elem.id
            });
            for (let child of elem.children) {
                this.fillTree(tree, child, level + 1);
            }
        },
        setToCurrentMonth() {
            const now = moment();
            this.year = now.year();
            this.month = now.month();
        },
        colorClass(value) {
            return value > 0 ? "text-success" : "text-danger";
        },
        can,
        async fetchData() {
            this.$router.push(
                {
                    query: {
                        ...this.$route.query,
                        month: this.month !== null ? this.month + 1 : undefined,
                        year: this.year || undefined,
                        wallet: this.wallet || undefined,
                        project: this.project || undefined,
                        location: this.location || undefined
                    }
                },
                () => {}
            );

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

                // this.projects = data.projects;
                // this.categories = data.categories;
                // this.locations = data.locations;

                // this.revenueByCategory = data.revenueByCategory;

                // this.revenueBySecondaryCategory =
                //     data.revenueBySecondaryCategory;
                // this.revenueByProject = data.revenueByProject;

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
