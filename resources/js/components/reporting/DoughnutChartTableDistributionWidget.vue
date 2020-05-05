<template>
    <div class="card mb-4">
        <div class="card-header">{{ title }}</div>
        <div class="card-body">
            <doughnut-chart
                :title="title"
                :url="url"
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
                            :value="value"
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
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    components: {
        DoughnutChart
    },
    mixins: [
        numberFormatMixin
    ],
    props: {
        data: {
            required: true,
            type: Object
        },
        title: {
            required: true,
            type: String
        },
        url: {
            required: true,
            type: String
        }
    },
    computed: {
        total () {
            return Object.values(this.data).reduce((a,b) => a + b, 0)
        }
    }
}
</script>
