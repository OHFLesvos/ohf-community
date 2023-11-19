<template>
    <b-container>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div v-if="periods">
            <div v-if="Object.keys(periods).length">
                <p v-html="$t('Please choose a month with unbooked transactions from {wallet} in an open booking period:', { wallet: walletObj.name })"></p>
                <div v-for="(period, periodId) in periods" :key="periodId">
                    <h2>{{ period.title }}</h2>
                    <b-list-group
                        v-if="period.months.length"
                        class="shadow-sm mb-4 mt-3"
                    >
                        <b-list-group-item
                            v-for="month in period.months"
                                :key="month.date"
                                :to="{ name: 'accounting.webling.prepare', params: {
                                    wallet: wallet,
                                }, query: {
                                    period: periodId,
                                    from: isoDateFormat(month.date),
                                    to: isoDateFormat(moment(month.date).endOf('month'))
                                }}"
                            >
                            <b-row>
                                <b-col>{{ moment(month.date).format('MMMM YYYY') }}</b-col>
                                <b-col cols="auto"><small>{{ month.transactions }} {{ $t('Transactions') }}</small></b-col>
                            </b-row>
                        </b-list-group-item>
                    </b-list-group>
                    <b-alert v-else variant="info" show>{{ $t('No months with unbooked transactions found.') }}</b-alert>
                </div>
            </div>
            <b-alert v-else variant="info" show>{{ $t('No matching open booking periods found.') }}</b-alert>
        </div>
        <p v-else-if="!loaded">
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import moment from 'moment/min/moment-with-locales';
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";

import weblingApi from "@/api/accounting/webling";

export default {
    title() {
        return this.$t("Book to Webling");
    },
    components: {
        AlertWithRetry
    },
    props: {
        wallet: {
            required: true
        }
    },
    data() {
        return {
            errorText: null,
            loaded: false,
            periods: null,
            walletObj: null,
        };
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.errorText = null;
            this.loaded = false
            try {
                let res = await weblingApi.listPeriods(this.wallet)
                this.walletObj = res.wallet
                this.periods = res.periods
            } catch (ex) {
                this.errorText = ex;
            }
            this.loaded = true
        },
        moment
    }
};
</script>
