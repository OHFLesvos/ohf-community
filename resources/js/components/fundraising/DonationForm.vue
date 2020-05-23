<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit, invalid }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-form-row>

                <!-- Date -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.date')"
                        vid="date"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.date')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-datepicker
                                v-model="form.date"
                                :max="maxDate"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Currency -->
                <b-col md="auto">
                    <validation-provider
                        :name="$t('fundraising.currency')"
                        vid="currency"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('fundraising.currency')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-select
                                v-model="form.currency"
                                :options="currencyOptions"
                                required
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Amount -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.amount')"
                        vid="amount"
                        :rules="{ required: true, decimal: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.amount')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                ref="amount"
                                v-model="form.amount"
                                type="number"
                                min="1"
                                required
                                step="any"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Exchange rate -->
                <b-col
                    v-if="form.currency != baseCurrency"
                    md
                >
                    <validation-provider
                        :name="$t('fundraising.exchange_rate')"
                        vid="exchange_rate"
                        :rules="{ decimal: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('fundraising.exchange_rate')"
                            :description="$t('fundraising.leave_empty_for_automatic_calculation')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.exchange_rate"
                                type="number"
                                min="0"
                                step="any"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>
            <b-form-row>

                <!-- Channel -->
                <b-col md="4">
                    <validation-provider
                        :name="$t('fundraising.channel')"
                        vid="channel"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('fundraising.channel')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.channel"
                                required
                                list="channel-list"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                        <b-form-datalist
                            id="channel-list"
                            :options="channels"
                        />
                    </validation-provider>
                </b-col>

                <!-- Purpose -->
                <b-col md>
                    <validation-provider
                        :name="$t('fundraising.purpose')"
                        vid="purpose"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('fundraising.purpose')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.purpose"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>
            <b-form-row>

                <!-- Reference -->
                <b-col md="4">
                    <validation-provider
                        :name="$t('fundraising.reference')"
                        vid="reference"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('fundraising.reference')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.reference"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- In name of -->
                <b-col md>
                    <validation-provider
                        :name="$t('fundraising.in_name_of')"
                        vid="in_name_of"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="`${$t('fundraising.in_name_of')}...`"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.in_name_of"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <p>
                <b-form-checkbox
                    v-model="form.thanked"
                >
                    {{ $t('fundraising.donor_thanked') }}
                </b-form-checkbox>
            </p>

            <p>

                <!-- Submit -->
                <b-button
                    type="submit"
                    variant="primary"
                    :disabled="disabled || invalid"
                >
                    <font-awesome-icon icon="check" />
                    {{ donation ? $t('app.update') : $t('app.add') }}
                </b-button>

                <!-- Cancel -->
                <b-button
                    variant="link"
                    :disabled="disabled"
                    @click="$emit('cancel')"
                >
                    {{ $t('app.cancel') }}
                </b-button>

                <!-- Delete -->
                <b-button
                    v-if="donation && donation.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger float-right"
                    @click="onDelete"
                >
                    {{ $t('app.delete') }}
                </b-button>

            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import moment from 'moment'
export default {
    props: {
        donation: {
            type: Object,
            required: false
        },
        currencies: {
            required: true,
            type: Object
        },
        channels: {
            required: true,
            type: Array
        },
        baseCurrency: {
            required: true
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.donation ? {
                    date: this.donation.date,
                    currency: this.donation.currency,
                    amount: this.donation.amount,
                    exchange_rate: this.donation.exchange_rate,
                    channel: this.donation.channel,
                    purpose: this.donation.purpose,
                    reference: this.donation.reference,
                    in_name_of: this.donation.in_name_of,
                    thanked: this.donation.thanked != null
                } : {
                    date: moment().format(moment.HTML5_FMT.DATE),
                    currency: this.baseCurrency,
                    amount: null,
                    exchange_rate: null,
                    channel: null,
                    purpose: null,
                    reference: null,
                    in_name_of: null,
                    thanked: false
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
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('fundraising.confirm_delete_donation'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
