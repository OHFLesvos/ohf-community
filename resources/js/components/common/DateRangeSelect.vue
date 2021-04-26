<template>
    <div class="form-row">
        <div :class="[ noGranularity ? 'col-md' : 'col-md-8' ]">
            <b-input-group :prepend="$t('Date range')" class="mb-2">
                <b-form-datepicker
                    v-model="from"
                    :placeholder="$t('From')"
                    :min="min"
                    :max="to"
                    :date-format-options="{ 'year': 'numeric', 'month': 'short', 'day': 'numeric', 'weekday': 'short' }"
                />
                <div class="input-group-prepend input-group-append">
                    <span class="input-group-text">:</span>
                </div>
                <b-form-datepicker
                    v-model="to"
                    :placeholder="$t('To')"
                    :min="from"
                    :max="max"
                    :date-format-options="{ 'year': 'numeric', 'month': 'short', 'day': 'numeric', 'weekday': 'short' }"
                />
            </b-input-group>
        </div>
        <div
            v-if="!noGranularity"
            class="col-md"
        >
            <b-input-group :prepend="$t('Granularity')" class="mb-2">
                <b-form-select
                    v-model="granularity"
                    :options="granularities"
                />
            </b-input-group>
        </div>
        <div class="col-auto">
            <b-button
                variant="secondary"
                class="mb-2"
                @click="reset()"
            >
                <font-awesome-icon icon="undo" />
            </b-button>
        </div>
    </div>
</template>

<script>
import { ucFirst } from '@/utils'
import moment from 'moment'
export default {
    props: {
        value: {
            type: Object,
            required: true,
            validator: function (obj) {
                return obj.from && obj.to
            }
        },
        noGranularity: Boolean,
        min: {
            type: String,
            required: false,
            default: null
        },
        max: {
            type: String,
            required: false,
            default: function () {
                return moment().format(moment.HTML5_FMT.DATE)
            }
        }
    },
    data () {
        return {
            originalValues: { ...this.value },
            from: this.value.from,
            to: this.value.to,
            granularity: this.value.granularity,
            granularities: [
                {
                    value: 'days',
                    text: ucFirst(this.$t('days'))
                },
                {
                    value: 'weeks',
                    text: ucFirst(this.$t('Weeks'))
                },
                {
                    value: 'months',
                    text: ucFirst(this.$t('Months'))
                },
                {
                    value: 'years',
                    text: ucFirst(this.$t('Years'))
                }
            ],
        }
    },
    watch: {
        from () {
            this.emitChange()
        },
        to () {
            this.emitChange()
        },
        granularity () {
            this.emitChange()
        }
    },
    methods: {
        emitChange () {
            if (this.from && this.to) {
                this.$emit('input', {
                    from: this.from,
                    to: this.to,
                    granularity: this.granularity
                })
            }
        },
        reset () {
            this.from = this.originalValues.from
            this.to = this.originalValues.to
            this.granularity = this.originalValues.granularity
        }
    }
}
</script>
