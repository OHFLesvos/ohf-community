<script>
import { applyColorPaletteToDatasets } from '@/utils'
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
        data: {
            type: [Function, Object],
            required: true
        },
        params: {
            type: Object,
            required: false
        },
        xLabel: {
            type: String,
            required: false
        },
        yLabel: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            options: this.createOptions()
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        refresh () {
            this.loadData()
        },
        async loadData () {
            try {
                let resolvedData;
                if (typeof this.data === 'function') {
                    resolvedData = await this.data(this.params ?? {})
                } else {
                    resolvedData = this.data
                }
                this.chartData = this.parseDateFromResponse(resolvedData)
            } catch (err) {
                console.error(err)
            }
        },
        parseDateFromResponse (resData) {
            // Assign lables and data
            const chartData = {
                'labels': resData.labels,
                'datasets': [],
            };
            resData.datasets.forEach((dataset) => {
                chartData.datasets.push({
                    label: dataset.label,
                    data: dataset.data
                })
            })

            // Assign colors to datasets
            applyColorPaletteToDatasets(chartData.datasets)

            return chartData
        },
        createOptions () {
            return {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        },
                        scaleLabel: {
                            display: this.xLabel,
                            labelString: this.xLabel
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true,
                        },
                        ticks: {
                            suggestedMin: 0,
                        },
                        scaleLabel: {
                            display: this.yLabel,
                            labelString: this.yLabel
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                }
            }
        }
    }
}
</script>
