<template>
    <div>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div class="row mb-2">
            <div class="col-sm">
                <div class="form-row">
                    <div class="col-auto mb-2">
                        <b-input-group :prepend="$t('Wallet')">
                            <b-select
                                v-model="wallet"
                                :options="walletOptions"
                                :disabled="isBusy"
                            />
                        </b-input-group>
                    </div>
                    <div v-if="allProjects.length > 0" class="col-auto mb-2">
                        <b-select
                            v-model="project"
                            :options="projectOptions"
                            :disabled="isBusy"
                        />
                    </div>
                    <div
                        v-if="useLocations && allLocations.length"
                        class="col-auto mb-2"
                    >
                        <b-select
                            v-model="location"
                            :options="locationOptions"
                            :disabled="isBusy"
                        />
                    </div>
                </div>
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
            v-if="!isBusy && isLoaded"
            hover
            responsive
            :items="wallets"
            :fields="walletFields"
            class="shadow-sm mb-4 bg-white"
            :caption="heading"
            caption-top
        >
            <template #cell(name)="data">
                <router-link
                    v-if="can('view-transactions')"
                    :to="{
                        name: 'accounting.transactions.index',
                        query: {
                            wallet: data.item.id,
                            'filter[date_start]': filterDateStart,
                            'filter[date_end]': filterDateEnd
                        }
                    }"
                >
                    {{ data.value }}
                </router-link>
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
                        <strong>{{ totals.income_formatted }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ totals.spending_formatted }}</strong>
                    </b-td>
                    <b-td class="text-right" :class="colorClass(totals.difference)">
                        <strong>{{ totals.difference_formatted }}</strong>
                    </b-td>
                    <b-td class="text-right">
                        <strong>{{ totals.fees_formatted }}</strong>
                    </b-td>
                    <b-td class="text-right" :class="colorClass(totals.amount)">
                        <strong>{{ totals.amount_formatted }}</strong>
                    </b-td>
                </b-tr>
            </template>
        </b-table>

        <template v-if="!isBusy && isLoaded">
            <b-row>
                <!-- Revenue by categories -->
                <b-col md>
                    <SummaryList
                        :items="categories"
                        :title="$t('Categories')"
                        paramName="category_id"
                        :wallet="wallet"
                        :filterDateStart="filterDateStart"
                        :filterDateEnd="filterDateEnd"
                        flatten-children
                    />
                </b-col>
                <!-- Revenue by project -->
                <b-col md>
                    <SummaryList
                        :items="projects"
                        :title="$t('Projects')"
                        paramName="project_id"
                        :noNameLabel="$t('No project')"
                        :wallet="wallet"
                        :filterDateStart="filterDateStart"
                        :filterDateEnd="filterDateEnd"
                        flatten-children
                    />
                </b-col>
            </b-row>
            <b-row>
                <!-- Revenue by secondary category -->
                <b-col v-if="secondCategories" md>
                    <SummaryList
                        :items="secondCategories"
                        :title="$t('Secondary Categories')"
                        paramName="secondary_category"
                        :noNameLabel="$t('No Secondary Category')"
                        :wallet="wallet"
                        :filterDateStart="filterDateStart"
                        :filterDateEnd="filterDateEnd"
                    />
                </b-col>
                <!-- Revenue by location -->
                <b-col v-if="locations" md>
                    <SummaryList
                        :items="locations"
                        :title="$t('Locations')"
                        paramName="location"
                        :noNameLabel="$t('No location')"
                        :wallet="wallet"
                        :filterDateStart="filterDateStart"
                        :filterDateEnd="filterDateEnd"
                    />
                </b-col>
            </b-row>
        </template>
    </div>
</template>

<script>
import moment from "moment";
import summaryApi from "@/api/accounting/summary";
import walletsApi from "@/api/accounting/wallets";
import transactionsApi from "@/api/accounting/transactions";
import projectsApi from "@/api/accounting/projects";
import AlertWithRetry from "@/components/alerts/AlertWithRetry";
import SummaryList from "@/components/accounting/SummaryList";
export default {
    title() {
        return this.$t("Summary");
    },
    components: {
        AlertWithRetry,
        SummaryList
    },
    data() {
        return {
            year: this.$route.query.year ?? null,
            month: this.$route.query.month ? this.$route.query.month - 1 : null,
            years: [moment().year()],

            wallets: [],
            totals: {},
            categories: [],
            projects: [],
            secondCategories: [],
            locations: [],

            allWallets: [],
            wallet: this.$route.query.wallet ?? null,
            allProjects: [],
            project: this.$route.query.project ?? null,

            useLocations: false,
            allLocations: [],
            location: this.$route.query.location ?? null,

            isLoaded: false,
            isBusy: false,
            errorText: null,

            walletFields: [
                {
                    key: "name",
                    label: this.$t("Wallet")
                },
                {
                    key: "income_formatted",
                    label: this.$t("Income"),
                    class: "text-right"
                },
                {
                    key: "spending_formatted",
                    label: this.$t("Spending"),
                    class: "text-right"
                },
                {
                    key: "difference_formatted",
                    label: this.$t("Difference"),
                    class: "text-right",
                    tdClass: (value, idx, item) => this.colorClass(item.difference)
                },
                {
                    key: "fees_formatted",
                    label: this.$t("Fees"),
                    class: "text-right"
                },
                {
                    key: "amount_formatted",
                    label: this.$t("Balance"),
                    class: "text-right",
                    tdClass: (value, idx, item) => this.colorClass(item.amount)
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
                ...this.allWallets.map(e => ({
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
            for (let elem of this.allProjects) {
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
                ...this.allLocations.map(e => ({
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
        month() {
            this.fetchData();
        },
        year() {
            this.fetchData();
        },
        wallet() {
            this.fetchData();
        },
        project() {
            this.fetchData();
        },
        location() {
            this.fetchData();
        }
    },
    async created() {
        this.allWallets = (await walletsApi.list()).data;
        this.allProjects = await projectsApi.tree();
        this.allLocations = await transactionsApi.locations();

        await this.fetchData();
        this.isLoaded = true;
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
            if (value > 0) {
                return "text-success"
            }
            if (value < 0) {
                return "text-danger";
            }
            return null;
        },
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

                this.projects = data.projects;
                this.categories = data.categories;

                this.secondCategories = data.second_categories;
                this.locations = data.locations;

                this.useLocations = data.use_locations;

                this.isBusy = false;
            } catch (err) {
                this.errorText = err;
            }
        }
    }
};
</script>

<style>
table caption {
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
}
</style>
