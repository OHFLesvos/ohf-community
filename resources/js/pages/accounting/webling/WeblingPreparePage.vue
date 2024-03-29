<template>
    <b-container fluid>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div v-if="period">
            <p v-html="$t('The following transactions from {wallet} from {from} to {to} can be booked in the period {period}:', { wallet: walletObj.name, from: isoDateFormat(from), to: isoDateFormat(to), period: period.title })"></p>
            <div v-if="transactions.length">
                <b-table-simple hover responsive small class="bg-white" id="bookings_table">
                    <b-thead>
                        <b-tr>
                            <th class="fit">{{ $t('Date') }}</th>
                            <th class="fit text-right">{{ $t('Credit') }}</th>
                            <th class="fit text-right">{{ $t('Debit') }}</th>
                            <th>{{ $t('Posting text') }}</th>
                            <th>{{ $t('Debit side') }}</th>
                            <th>{{ $t(' Credit side') }}</th>
                            <th class="fit">{{ $t('Receipt No.') }}</th>
                            <th class="fit">{{ $t('Controlled') }}</th>
                            <th class="fit">{{ $t('Action') }}</th>
                        </b-tr>
                    </b-thead>
                    <b-tbody>
                        <b-tr v-for="(transaction, idx) in transactions" :key="transaction.id" :class="tableRowClass(idx)">
                            <b-td class="fit align-middle">
                                <router-link :to="{ name: 'accounting.transactions.show', params: { id: transaction.id } }"
                                    target="_blank"
                                    :title="$t('Open in new window/tab')"
                                >{{ dateFormat(transaction.date) }}</router-link>
                            </b-td>
                            <b-td class="text-success text-right fit align-middle">
                                <template v-if="transaction.type == 'income'">
                                    {{ transaction.amount | decimalNumberFormat }}
                                </template>
                            </b-td>
                            <b-td class="text-danger text-right fit align-middle">
                                <template v-if="transaction.type == 'spending'">
                                    {{ transaction.amount | decimalNumberFormat }}
                                </template>
                            </b-td>
                            <b-td class="align-middle">
                                <b-form-input
                                    v-model="preparedTransactions[idx].posting_text"
                                    :placeholder="$t('Posting text')"
                                    required
                                    :disabled="isBusy"
                                />
                            </b-td>
                            <b-td style="max-width: 8em" class="align-middle">
                                <b-form-select
                                    v-if="transaction.type == 'income'"
                                    v-model="preparedTransactions[idx].debit_side"
                                    :options="assetsSelectMoneyTo"
                                    :disabled="isBusy"
                                />
                                <b-form-select
                                    v-if="transaction.type == 'spending'"
                                    v-model="preparedTransactions[idx].debit_side"
                                    :options="expenseSelect"
                                    :disabled="isBusy"
                                />
                            </b-td>
                            <b-td style="max-width: 8em" class="align-middle">
                                <b-form-select
                                    v-if="transaction.type == 'income'"
                                    v-model="preparedTransactions[idx].credit_side"
                                    :options="incomeSelect"
                                    :disabled="isBusy"
                                />
                                <b-form-select
                                    v-if="transaction.type == 'spending'"
                                    v-model="preparedTransactions[idx].credit_side"
                                    :options="assetsSelectPaidFrom"
                                    :disabled="isBusy"
                                />
                            </b-td>
                            <b-td class="fit align-middle">{{ transaction.receipt_no }}</b-td>
                            <b-td class="fit text-center align-middle">
                                <font-awesome-icon :icon="transaction.controlled_at ? 'check' : 'times'" :class="transaction.controlled_at ? 'text-success' : 'text-danger'"/>
                            </b-td>
                            <td class="fit align-middle">
                                <b-radio-group
                                    v-model="preparedTransactions[idx].action"
                                    :options="actionOptions"
                                    stacked
                                    :disabled="isBusy"
                                />
                            </td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
                <p>
                    <b-button
                        variant="primary"
                        type="submit"
                        :disabled="isBusy"
                        @click="handleSubmit"
                    >
                        <font-awesome-icon icon="check" />
                        {{ $t('Submit') }}
                    </b-button>
                </p>
            </div>
            <b-alert v-else variant="info" show>
                {{ $t('No transactions found.') }}
            </b-alert>
        </div>
        <p v-else-if="!loaded">
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";

import { showSnackbar } from '@/utils'
import weblingApi from "@/api/accounting/webling";

export default {
    title() {
        return this.$t("Accounting");
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
            isBusy: false,
            period: null,
            walletObj: null,
            from: this.$route.query.from,
            to: this.$route.query.to,
            transactions: [],
            actionOptions: null,
            assetsSelectMoneyTo: null,
            assetsSelectPaidFrom: null,
            expenseSelect: null,
            incomeSelect: null,
            preparedTransactions: [],
        };
    },
     async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.loaded = false
            this.errorText = null;
            try {
                let res = await weblingApi.prepare(this.wallet, this.$route.query.period, this.$route.query.from, this.$route.query.to)
                this.walletObj = res.wallet
                this.period = res.period
                this.transactions = res.transactions

                this.preparedTransactions = this.transactions.map(t => ({
                    id: t.id,
                    posting_text: `${t.category_name} - ${(t.project_name ? t.project_name + ' - ' : '')}${t.description}`,
                    debit_side: null,
                    credit_side: null,
                    action: res.defaultAction
                }))

                this.assetsSelectMoneyTo = [
                    { text: this.$t('Money to'), value: null },
                    ...Object.entries(res.assetsSelect).map(e => ({
                        label: e[0],
                        options: Object.entries(e[1]).map(f => ({ text: f[1], value: f[0] }))
                    }))
                ];
                this.assetsSelectPaidFrom = [
                    { text: this.$t('Paid from'), value: null },
                    ...Object.entries(res.assetsSelect).map(e => ({
                    label: e[0],
                    options: Object.entries(e[1]).map(f => ({ text: f[1], value: f[0] }))
                    }))
                ];
                this.expenseSelect = [
                    { text: this.$t('Paid for'), value: null },
                    ...Object.entries(res.expenseSelect).map(e => ({
                    label: e[0],
                    options: Object.entries(e[1]).map(f => ({ text: f[1], value: f[0] }))
                    }))
                ];
                this.incomeSelect = [
                    { text: this.$t('Received for'), value: null },
                    ...Object.entries(res.incomeSelect).map(e => ({
                    label: e[0],
                    options: Object.entries(e[1]).map(f => ({ text: f[1], value: f[0] }))
                    }))
                ];
                this.actionOptions = Object.entries(res.actions).map(e => ({ text: e[1], value: e[0] }))
            } catch (ex) {
                this.errorText = ex;
            }
            this.loaded = true
        },
        tableRowClass(idx) {
            if (this.preparedTransactions[idx].action == 'ignore')  {
                if (this.preparedTransactions[idx].debit_side && this.preparedTransactions[idx].credit_side) {
                    return 'table-secondary'
                }
                return
            }
            if (this.preparedTransactions[idx].posting_text.trim().length && this.preparedTransactions[idx].debit_side && this.preparedTransactions[idx].credit_side) {
                return 'table-success'
            }
            return 'table-warning'
        },
        async handleSubmit() {
            let transactions = this.preparedTransactions.filter(pt => pt.action == 'book' && pt.posting_text.trim().length && pt.debit_side && pt.credit_side)
            if (transactions.length == 0) {
                alert(this.$t('No transaction are ready for booking.'))
                return
            }

            this.isBusy = true
            try {
                let formData = {
                    period:this.$route.query.period,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    transactions: transactions
                }
                let result = await weblingApi.store(this.wallet, formData)
                showSnackbar(this.$t(result.info))
                this.$router.push({ name: 'accounting.webling.index', params: { wallet: this.wallet }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
};
</script>
