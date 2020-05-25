<template>
    <b-card
        :header="title"
        class="mb-4"
        no-body
    >
        <b-card-body>
            <template v-if="!data">
                {{ $t('app.loading') }}
            </template>
            <doughnut-chart
                :title="title"
                :data-provider="fetchData"
                :height="300"
                class="mb-2">
            </doughnut-chart>
        </b-card-body>
        <b-table-simple
            v-if="data"
            responsive
            small
            class="my-0"
        >
            <b-tr
                v-for="(value, label) in data"
                :key="label"
            >
                <b-td class="fit">
                    {{ label }}
                </b-td>
                <b-td class="align-middle d-none d-sm-table-cell">
                    <b-progress
                        :value="value"
                        :max="total"
                        :show-value="false"
                        variant="secondary"
                    />
                </b-td>
                <b-td class="fit text-right">
                    {{ percentValue(value, total) }}%
                </b-td>
                <b-td class="fit text-right d-none d-sm-table-cell">
                    {{ numberFormat(value) }}
                </b-td>
            </b-tr>
        </b-table-simple>
    </b-card>
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
        title: {
            required: true,
            type: String
        },
        dataProvider: {
            type: Function,
            required: true
        }
    },
    data () {
        return {
            data: null,
        }
    },
    computed: {
        total () {
            return Object.values(this.data).reduce((a,b) => a + b, 0)
        }
    },
    methods: {
        async fetchData () {
            this.data = await this.dataProvider()
            return this.data
        }
    }
}
</script>
