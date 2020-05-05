<script>
import palette from 'google-palette'
import axios from '@/plugins/axios'
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
        url: {
            type: String,
            required: true
        },
        type: {
            type: String,
            required: false,
            default: 'pie'
        },
        showLegend: Boolean
    },
    mounted () {
        this.addPlugin(ChartJsPluginDataLabels)
        axios.get(this.url)
            .then((res) => this.renderChart(this.getChartData(res.data), this.getOptions()))
            .catch(err => console.error(err))
    },
    methods: {
        getChartData(resData) {
            const labels = Object.keys(resData)
            if (labels.length == 0) {
                return this.noDataData()
            }
            const dataset = Object.values(resData)
            const colorPalette = palette('tol', Math.min(dataset.length, 12))
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
                'labels': [this.$t('app.no_data')],
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
                    display: this.showLegend,
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
                        formatter: (value, context) => {
                            var dataset = context.chart.data.datasets[0]
                            var meta = dataset._meta[Object.keys(dataset._meta)[0]]
                            var total = meta.total
                            var currentValue = dataset.data[context.dataIndex]
                            var label = context.chart.data.labels[context.dataIndex]
                            var percentage = parseFloat((currentValue / total * 100).toFixed(1))
                            if (this.showLegend) {
                                return `${percentage}%`
                            }
                            return `${label}\n(${percentage}%)`
                        }
                    }
                }
            }
        },
        toolTipLabel (tooltipItem, data) {
            // Percentage in tooltips (see https://stackoverflow.com/questions/37257034/chart-js-2-0-doughnut-tooltip-percentages/49717859#49717859)
            var dataset = data.datasets[tooltipItem.datasetIndex]
            var meta = dataset._meta[Object.keys(dataset._meta)[0]]
            var total = meta.total
            var currentValue = dataset.data[tooltipItem.index]
            var percentage = parseFloat((currentValue/total*100).toFixed(1))
            return `${currentValue} (${percentage}%)`
        },
        toolTipTitle (tooltipItem, data) {
            return data.labels[tooltipItem[0].index]
        }
    }
}
</script>
