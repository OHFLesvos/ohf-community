<template>
    <b-form @submit.stop.prevent="handleSubmit">
        <b-form-row>
            <b-col md>
                <b-form-group>
                    <b-form-datepicker
                        v-model="form.date"
                        :max="maxDate"
                        required
                    />
                </b-form-group>
            </b-col>
            <b-col md="auto">
                <b-form-group>
                    <b-select
                        v-model="form.currency"
                        :options="currencyOptions"
                        required
                    />
                </b-form-group>
            </b-col>
            <b-col md>
                <b-form-group>
                    <b-form-input
                        ref="amount"
                        v-model="form.amount"
                        type="number"
                        min="1"
                        required
                        :placeholder="$t('app.amount')"
                        step="any"
                    />
                </b-form-group>
            </b-col>
            <b-col
                v-if="form.currency != baseCurrency"
                md
            >
                <b-form-group>
                    <b-form-input
                        v-model="form.exchange_rate"
                        type="number"
                        min="0"
                        :placeholder="$t('fundraising.optional_exchange_rate')"
                        step="any"
                        :title="$t('fundraising.leave_empty_for_automatic_calculation')"
                    />
                </b-form-group>
            </b-col>
        </b-form-row>
        <b-form-row>
            <b-col md>
                <b-form-group>
                    <b-form-input
                        v-model="form.channel"
                        required
                        :placeholder="$t('fundraising.channel')"
                        list="channel-list"
                    />
                </b-form-group>
                <b-form-datalist
                    id="channel-list"
                    :options="channels"
                />
            </b-col>
            <b-col md>
                <b-form-group>
                    <b-form-input
                        v-model="form.purpose"
                        :placeholder="$t('fundraising.purpose')"
                    />
                </b-form-group>
            </b-col>
            <b-col md>
                <b-form-group>
                    <b-form-input
                        v-model="form.reference"
                        :placeholder="$t('fundraising.reference')"
                    />
                </b-form-group>
            </b-col>
        </b-form-row>
        <b-form-group>
            <b-form-input
                v-model="form.in_name_of"
                :placeholder="`${$t('fundraising.in_name_of')}...`"
            />
        </b-form-group>
        <p>
            <b-button
                type="submit"
                variant="primary"
                :disabled="disabled"
            >
                <font-awesome-icon icon="check" />
                {{ $t('app.add') }}
            </b-button>

            <b-button
                variant="link"
                :disabled="disabled"
                @click="$emit('cancel')"
            >
                {{ $t('app.cancel') }}
            </b-button>
        </p>
    </b-form>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        currencies: {
            required: true,
            type: Object
        },
        channels: {
            required: true,
            type: Array
        },
        baseCurrency: {
            required: true,
            type: String
        },
        disabled: Boolean
    },
    data () {
        return {
            form: {
                date: moment().format(moment.HTML5_FMT.DATE),
                currency: this.baseCurrency,
                amount: null,
                exchange_rate: null,
                channel: null,
                purpose: null,
                reference: null,
                in_name_of: null,
            },
        }
    },
    computed: {
        maxDate () {
            return moment().format(moment.HTML5_FMT.DATE)
        },
        currencyOptions () {
            return Object.entries(this.currencies).map(function(e) {
                return {
                   value: e[0],
                   text: e[1]
                }
            })
        }
    },
    methods: {
        handleSubmit () {
            this.$emit('submit', this.form)
        }
    }
}
</script>
