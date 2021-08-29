<script>
import palette from 'google-palette'
import { Doughnut } from 'vue-chartjs'
import ChartJsPluginDataLabels from 'chartjs-plugin-datalabels'
import { Chart } from 'chart.js'
Chart.plugins.unregister(ChartJsPluginDataLabels)
export default {
    extends: Doughnut,
    props: {
        title: {
            type: String,
            required: true
        },
        data: {
            type: [Function, Object],
            required: true
        },
        limit: {
            type: Number,
            required: false,
            default: 12 // Max number of colors in 'tol' palette
        },
        hideLegend: Boolean
    },
    async mounted () {
        this.addPlugin(ChartJsPluginDataLabels)
        this.loadData()
    },
    methods: {
        async loadData () {
            try {
                let chartData;
                if (typeof this.data === 'function') {
                    chartData = await this.data()
                } else {
                    chartData = this.data
                }
                this.renderChart(this.getChartData(chartData), this.getOptions())
            } catch (err) {
                console.error(err)
            }
        },
        getChartData(resData) {
            let labels = Object.keys(resData)
            if (labels.length == 0) {
                return this.noDataData()
            }
            let dataset = Object.values(resData)

            if (dataset.length > this.limit) {
                let others = dataset.slice(this.limit - 1).reduce((a, item) => a + item, 0)
                dataset = dataset.slice(0, this.limit - 1)
                dataset.push(others)
                labels = labels.slice(0, this.limit - 1)
                labels.push(this.$t('Others'))
            }

            const colorPalette = palette('tol', Math.min(dataset.length, this.limit))
            const colors = Array(colorPalette.length)
                .fill()
                .map((e, i) => '#' + colorPalette[i %  colorPalette.length])
            return {
                'labels': labels,
                'datasets': [{
                    data: dataset,
                    backgroundColor: colors,
                }]
            }
        },
        noDataData () {
            return {
                'labels': [this.$t('No data')],
                'datasets': [{
                    data: [1],
                    datalabels: {
                        color: '#000000'
                    }
                }]
            }
        },
        getOptions() {
            return {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                    display: !this.hideLegend,
                    position: 'bottom'
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                },
                tooltips: {
                    callbacks: {
                        label: this.toolTipLabel,
                        title: this.toolTipTitle
                    }
                },
                plugins: {
                    datalabels: {
                        color: '#ffffff',
                        textAlign: 'center',
                        formatter: this.dataLabelFormat
                    }
                }
            }
        },
        toolTipLabel (tooltipItem, data) {
            // Percentage in tooltips (see https://stackoverflow.com/questions/37257034/chart-js-2-0-doughnut-tooltip-percentages/49717859#49717859)
            const dataset = data.datasets[tooltipItem.datasetIndex]
            const meta = dataset._meta[Object.keys(dataset._meta)[0]]
            const total = meta.total
            const currentValue = dataset.data[tooltipItem.index]
            const percentage = parseFloat((currentValue/total*100).toFixed(1))
            return `${this.numberFormat(currentValue)} (${percentage}%)`
        },
        toolTipTitle (tooltipItem, data) {
            return data.labels[tooltipItem[0].index]
        },
        dataLabelFormat (value, context) {
            const dataset = context.chart.data.datasets[0]
            const meta = dataset._meta[Object.keys(dataset._meta)[0]]
            const total = meta.total
            const currentValue = dataset.data[context.dataIndex]
            const label = context.chart.data.labels[context.dataIndex]
            const percentage = parseFloat((currentValue / total * 100).toFixed(1))
            if (!this.hideLegend) {
                return `${percentage}%`
            }
            return `${label}\n(${percentage}%)`
        }
    }
}
</script>
