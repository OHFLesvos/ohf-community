<script>
    import palette from 'google-palette'
    const colorPalette = palette('tol', 12);

    import axios from '@/plugins/axios'

    import { Bar } from 'vue-chartjs'
    export default {
        extends: Bar,
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
                required: false
            },
            legend: {
                type: Boolean,
                required: false,
                default: true
            },
        },
        mounted () {
            this.loadData()
        },
        methods: {
            loadData () {
                console.log(`loaded chart data ${this.url}`)
                axios.get(this.url)
                    .then((res) => {
                        console.log('loaded chart data')
                        const chartData = this.parseDateFromResponse(res)
                        this.renderChart(chartData, this.createOptions());
                });
            },
            parseDateFromResponse (res) {
                // Assign lables and data
                const data = {
                    'labels': res.data.labels,
                    'datasets': [],
                };
                Object.keys(res.data.datasets).forEach(function (key) {
                    data.datasets.push({
                        label: key,
                        data: res.data.datasets[key],
                        barPercentage: 1
                    });
                });

                // Assign colors to datasets
                for (var i = 0; i < data.datasets.length; i++) {
                    data.datasets[i].backgroundColor = '#' + colorPalette[i %  colorPalette.length];
                    data.datasets[i].borderColor = '#' + colorPalette[i %  colorPalette.length];
                    data.datasets[i].fill = false;
                }

                return data
            },
            createOptions () {
                const options = {
                    title: {
                        display: true,
                        text: this.title,
                    },
                    legend: {
                        display: this.legend,
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
                                display: true,
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                suggestedMin: 0,
                            }
                        }]
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                };

                // Add y-axis label
                if (this.ylabel) {
                    options.scales.yAxes[0].scaleLabel= {
                        display: true,
                        labelString: this.ylabel,
                    };
                }

                return options
            }
        }
    }
</script>
