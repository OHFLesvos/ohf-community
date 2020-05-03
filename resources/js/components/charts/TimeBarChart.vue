<template>
    <bar-chart
        v-if="loaded"
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
            <em>{{ $t('app.loading') }}</em>
        </p>
    </div>
</template>

<script>
import palette from 'google-palette'
import axios from '@/plugins/axios'
import moment from 'moment'
import { hexAToRGBA } from '@/utils'
import BarChart from './BarChart'
export default {
    components: {
        BarChart
    },
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
        }
    },
    data () {
        return {
            loaded: false,
            chartData: {}
        }
    },
    computed: {
        url () {
            return `${this.baseUrl}?granularity=${this.granularity}&from=${this.dateFrom}&to=${this.dateTo}`
        },
        options () {
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
                            tooltipFormat: 'LL',
                            unit: 'day',
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
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: this.$t('app.quantity')
                        },
                        ticks: {
                            suggestedMin: 0,
                            precision: 0
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
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
            this.loaded = false
            axios.get(this.url)
                .then(res => {
                    this.chartData = this.chartDataFromResponse(res.data)
                    this.loaded = true
                })
                .catch(err => console.error(err))
        },
        chartDataFromResponse (resData) {
            const chartData = {
                labels: resData.labels,
                datasets: []
            }

            // Assign lables and data
            Object.keys(resData.datasets).forEach(function (key) {
                chartData.datasets.push({
                    label: key,
                    data: resData.datasets[key]
                })
            })

            // Assign colors to datasets
            const colorPalette = palette('tol', Math.min(chartData.datasets.length, 12))
            for (var i = 0; i < chartData.datasets.length; i++) {
                const hexcolor = '#' + colorPalette[i %  colorPalette.length]
                chartData.datasets[i].backgroundColor = hexAToRGBA(hexcolor + '80')
                chartData.datasets[i].borderColor = hexcolor
                chartData.datasets[i].borderWidth = 1
            }

            if (resData.time_unit) {
                switch (resData.time_unit) {
                    case 'year':
                        this.options.scales.xAxes[0].time.unit = 'year'
                        this.options.scales.xAxes[0].time.tooltipFormat = 'YYYY'
                        this.options.scales.xAxes[0].time.parser = 'YYYY'
                        break;
                    case 'month':
                        this.options.scales.xAxes[0].time.unit = 'month'
                        this.options.scales.xAxes[0].time.tooltipFormat = 'MMMM YYYY'
                        this.options.scales.xAxes[0].time.parser = 'YYYY-MM'
                        break;
                    case 'week':
                        this.options.scales.xAxes[0].time.unit = 'week'
                        this.options.scales.xAxes[0].time.tooltipFormat = '[W]WW GGGG'
                        this.options.scales.xAxes[0].time.parser = undefined
                        break;
                    default:
                        this.options.scales.xAxes[0].time.unit = 'day'
                        this.options.scales.xAxes[0].time.tooltipFormat = 'dddd, LL'
                        this.options.scales.xAxes[0].time.parser = 'YYYY-MM-DD'
                }
            }

            return chartData
        }
    }

}
</script>
