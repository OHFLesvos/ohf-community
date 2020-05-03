<script>
import palette from 'google-palette'
import axios from '@/plugins/axios'
import moment from 'moment'
import { hexAToRGBA } from '@/utils'
import { Bar, mixins } from 'vue-chartjs'
const { reactiveData } = mixins
export default {
    extends: Bar,
    mixins: [reactiveData],
    props: {
        title: {
            type: String,
            required: true
        },
        url: {
            type: String,
            required: true
        },
        ylabel: {
            type: String,
            required: false,
            default: function() {
                return this.$t('app.quantity')
            }
        },
        legend: {
            type: Boolean,
            required: false,
            default: true
        },
    },
    data() {
        return {
            options: {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                    display: this.legend,
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
                            display: (this.ylabel !== undefined),
                            labelString: this.ylabel
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
        'options': {
            handler () {
                this.renderChart(this.chartData, this.options)
            },
            deep: true
        }
    },
    mounted () {
        moment.locale(this.$i18n.locale);
        this.loadData()
    },
    methods: {
        loadData () {
            axios.get(this.url)
                .then(res => this.chartData = this.chartDataFromResponse(res.data))
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
