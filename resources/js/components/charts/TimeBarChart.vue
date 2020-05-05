<template>
    <component :is="cumulative ? 'reactive-line-chart' : 'reactive-bar-chart'"
        v-if="!error && loaded"
        :chart-data="chartData"
        :options="options"
        :height="height"
        class="border"
    />
    <div
        v-else
        class="d-flex border"
        :style="`height: ${height}px`"
    >
        <p class="justify-content-center align-self-center text-center w-100">
            <em v-if="error" class="text-danger">{{ error }}</em>
            <em v-else>{{ $t('app.loading') }}</em>
        </p>
    </div>
</template>

<script>
import axios from '@/plugins/axios'
import moment from 'moment'
import { applyColorPaletteToDatasets, getAjaxErrorMessage } from '@/utils'
import ReactiveBarChart from '@/components/charts/ReactiveBarChart'
import ReactiveLineChart from '@/components/charts/ReactiveLineChart'
import slugify from 'slugify'
import numberFormatMixin from '@/mixins/numberFormatMixin'
export default {
    components: {
        ReactiveBarChart,
        ReactiveLineChart
    },
    mixins: [
        numberFormatMixin
    ],
    props: {
        title: {
            type: String,
            required: true
        },
        baseUrl: {
            type: String,
            required: true
        },
        dateFrom: {
            type: String,
            required: true
        },
        dateTo: {
            type: String,
            required: true
        },
        granularity: {
            type: String,
            required: false,
            value: 'days'
        },
        height: {
            type: Number,
            required: false,
            default: 350
        },
        cumulative: Boolean
    },
    data () {
        return {
            loaded: false,
            error: null,
            chartData: {},
            units: new Map()
        }
    },
    computed: {
        url () {
            return `${this.baseUrl}?granularity=${this.granularity}&from=${this.dateFrom}&to=${this.dateTo}`
        },
        options () {
            let timeUnit, timeTooltipFormat, timeParser
            switch (this.granularity) {
                case 'years':
                    timeUnit = 'year'
                    timeTooltipFormat = 'YYYY'
                    timeParser = 'YYYY'
                    break;
                case 'months':
                    timeUnit = 'month'
                    timeTooltipFormat = 'MMMM YYYY'
                    timeParser = 'YYYY-MM'
                    break;
                case 'weeks':
                    timeUnit = 'week'
                    timeTooltipFormat = '[W]WW GGGG'
                    timeParser = undefined
                    break;
                default: // days
                    timeUnit = 'day'
                    timeTooltipFormat = 'dddd, LL'
                    timeParser = 'YYYY-MM-DD'
            }

            return {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        type: 'time',
                        time: {
                            tooltipFormat: timeTooltipFormat,
                            unit: timeUnit,
                            parser: timeParser,
                            minUnit: 'day',
                            isoWeekday: true,
                            displayFormats: {
                                day: 'll',
                                week: '[W]WW GGGG'
                            }
                        },
                        ticks: {
                            min: this.dateFrom,
                            max: this.dateTo
                        },
                        gridLines: {
                            display: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: this.$t('app.date')
                        }
                    }],
                    yAxes: this.yAxes()
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                },
                tooltips: {
                    callbacks: {
                        label: (tooltipItem, chart) => {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return `${datasetLabel}: ${this.numberFormat(tooltipItem.yLabel)}`
                        }
                    }
                }
            }
        }
    },
    watch: {
        url () {
            this.loadData()
        },
    },
    mounted () {
        moment.locale(this.$i18n.locale);
        this.loadData()
    },
    methods: {
        loadData () {
            this.error = null
            this.loaded = false
            axios.get(this.url)
                .then(res => {
                    this.chartData = this.chartDataFromResponse(res.data)
                    this.loaded = true
                })
                .catch(err => this.error = getAjaxErrorMessage(err))
        },
        chartDataFromResponse (resData) {
            const chartData = {
                labels: resData.labels,
                datasets: []
            }

            // Assign lables and data
            let units = new Map()
            resData.datasets.forEach((dataset) => {
                const axisID = slugify(dataset.unit)
                let data = dataset.data
                if (this.cumulative) {
                    let previous = 0
                    for (let i = 0; i < data.length; i++) {
                        if (data[i]) {
                            data[i] += previous
                            previous = data[i]
                        }
                    }
                }
                chartData.datasets.push({
                    label: dataset.label,
                    data: data,
                    yAxisID: axisID
                })
                units.set(axisID, dataset.unit)
            })
            this.units = units

            // Assign colors to datasets
            applyColorPaletteToDatasets(chartData.datasets)

            return chartData
        },
        yAxes () {
            const yAxes = []
            let i = 0
            for (let [key, value] of this.units) {
                yAxes.push({
                    display: true,
                    id: key,
                    position: i++ % 2 == 1 ? 'right' : 'left',
                    gridLines: {
                        display: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: value
                    },
                    ticks: {
                        suggestedMin: 0,
                        precision: 0,
                        callback: this.numberFormat
                    }
                })
            }
            return yAxes
        }
    }
}
</script>
