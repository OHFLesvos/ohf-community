<script>
import palette from 'google-palette'
import axios from '@/plugins/axios'
import { Pie } from 'vue-chartjs'
export default {
    extends: Pie,
    props: {
        title: {
            type: String,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },
    mounted () {
        axios.get(this.url)
            .then((res) => this.renderChart(this.getChartData(res.data), this.getOptions()))
            .catch(err => console.error(err))
    },
    methods: {
        getChartData(resData) {
            const labels = Object.keys(resData)
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
        getOptions() {
            return {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                    display: true,
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
            return currentValue + ' (' + percentage + '%)'
        },
        toolTipTitle (tooltipItem, data) {
            return data.labels[tooltipItem[0].index]
        }
    }
}
</script>
