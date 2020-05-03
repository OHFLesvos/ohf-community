<template>
    <div class="form-row">
        <div class="col-sm">
            <b-form-select
                v-model="granularity"
                :options="granularities"
                class="mb-2"
            />
        </div>
        <div class="col-sm">
            <b-form-datepicker
                v-model="from"
                :placeholder="$t('app.from')"
                :min="min"
                :max="to"
                class="mb-2"
            />
        </div>
        <div class="col-sm">
            <b-form-datepicker
                v-model="to"
                :placeholder="$t('app.to')"
                :min="from"
                :max="max"
                class="mb-2"
            />
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
                return obj.from && obj.to && obj.granularity
            }
        },
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
                    text: ucFirst(this.$t('app.days'))
                },
                {
                    value: 'weeks',
                    text: ucFirst(this.$t('app.weeks'))
                },
                {
                    value: 'months',
                    text: ucFirst(this.$t('app.months'))
                },
                {
                    value: 'years',
                    text: ucFirst(this.$t('app.years'))
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
