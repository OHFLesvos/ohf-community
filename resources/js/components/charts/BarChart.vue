<script>
import { applyColorPaletteToDatasets } from '@/utils'
import axios from '@/plugins/axios'
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
    watch: {
        url () {
            this.loadData()
        }
    },
    mounted () {
        this.loadData()
    },
    methods: {
        loadData () {
            axios.get(this.url)
                .then(res => this.chartData = this.parseDateFromResponse(res.data))
                .catch(err => console.error(err))
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
