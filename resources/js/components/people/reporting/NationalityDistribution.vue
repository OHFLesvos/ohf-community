<template>
    <div class="card mb-4">
        <div class="card-header">{{ $t('people.nationalities') }}</div>
        <div class="card-body">
            <doughnut-chart
                :title="$t('people.nationalities')"
                :url="route('api.people.reporting.nationalities')"
                :height="300"
                class="mb-2">
            </doughnut-chart>
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-sm my-0">
                <tr
                    v-for="(value, label) in data"
                    :key="label"
                >
                    <td class="fit">
                        {{ label }}
                    </td>
                    <td class="align-middle d-none d-sm-table-cell">
                        <b-progress
                            :value="v"
                            :max="total"
                            :show-value="false"
                            variant="secondary"
                        />
                    </td>
                    <td class="fit text-right">
                        {{ percentValue(value, total) }}%
                    </td>
                    <td class="fit text-right d-none d-sm-table-cell">
                        {{ numberFormat(value) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import DoughnutChart from '@/components/charts/DoughnutChart'
import { roundWithDecimals } from '@/utils'
import numeral from 'numeral'
export default {
    components: {
        DoughnutChart
    },
    props: {
        data: {
            required: true,
            type: Object
        }
    },
    computed: {
        total () {
            return Object.values(this.data).reduce((a,b) => a + b, 0)
        }
    },
    methods: {
        numberFormat (value) {
            return numeral(value).format('0,0')
        },
        percentValue (value, total) {
            return roundWithDecimals(value / total * 100, 1)
        }
    }
}
</script>
