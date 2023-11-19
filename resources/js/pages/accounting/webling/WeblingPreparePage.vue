<template>
    <b-container fluid>
        <alert-with-retry :value="errorText" @retry="fetchData" />
        <div v-if="period">
            <p v-html="$t('The following transactions from {from} to {to} can be booked in the period {period}:', { from: isoDateFormat(from), to: isoDateFormat(to), period: period.title })"></p>
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
                        <b-tr v-for="(transaction, idx) in transactions" :key="transaction.id">
                            <b-td class="fit align-middle">
                                <router-link :to="{ name: 'accounting.transactions.show', params: { id: transaction.id } }"
                                    target="_blank"
                                    :title="$t('Open in new window/tab')"
                                >{{ transaction.date }}</router-link>
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
                                    v-model="posting_text[idx]"
                                    :placeholder="$t('Posting text')"
                                    required
                                />
                            </b-td>
                            <b-td style="max-width: 8em" class="align-middle">
                                <b-form-select v-model="debit_side[idx]"/>
                                <!-- @if($transaction->type == 'income')
                                    {{ Form::bsSelect('debit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => __('Money to') ], '') }}
                                @elseif($transaction->type == 'spending')
                                    {{ Form::bsSelect('debit_side['.$transaction->id.']', $expenseSelect, null, [ 'placeholder' => __('Paid for') ], '') }}
                                @endif -->
                            </b-td>
                            <b-td style="max-width: 8em" class="align-middle">
                                <b-form-select v-model="credit_side[idx]"/>
                                <!-- @if($transaction->type == 'income')
                                    {{ Form::bsSelect('credit_side['.$transaction->id.']', $incomeSelect, null, [ 'placeholder' => __('Received for') ], '') }}
                                @elseif($transaction->type == 'spending')
                                    {{ Form::bsSelect('credit_side['.$transaction->id.']', $assetsSelect, null, [ 'placeholder' => __('Paid from') ], '') }}
                                @endif -->
                            </b-td>
                            <b-td class="fit align-middle">{{ transaction.receipt_no }}</b-td>
                            <b-td class="fit text-center align-middle">
                                <font-awesome-icon :icon="transaction.controlled_at ? 'check' : 'times'" :class="transaction.controlled_at ? 'text-success' : 'text-danger'"/>
                            </b-td>
                            <td class="fit align-middle">
                                <b-radio-group
                                    v-model="action[idx]"
                                    :options="actionOptions"
                                    stacked
                                />
                            </td>
                        </b-tr>
                    </b-tbody>
                </b-table-simple>
                <p>
                    <b-button variant="primary" type="submit">
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
import moment from 'moment/min/moment-with-locales';
import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";

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
            period: null,
            from: this.$route.query.from,
            to: this.$route.query.to,
            transactions: [],
            posting_text: [],
            debit_side: [],
            credit_side: [],
            action: [],
            actionOptions: null,
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
                console.log(res)
                this.period = res.period
                this.transactions = res.transactions
                this.posting_text = []
                this.transactions.forEach(t => {
                    this.posting_text.push(`${t.category?.name} - ${(t.project ? t.project?.name + ' - ' : '')}${t.description}`)
                    this.action.push(res.defaultAction)
                })
                this.actionOptions = Object.entries(res.actions).map(e => ({ text: e[1], value: e[0] }))
            } catch (ex) {
                this.errorText = ex;
            }
            this.loaded = true
        },
        moment
    }
};
</script>
