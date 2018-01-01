<script>
    var palette = require('google-palette');
    var colorPalette = palette('tol', 8);

    import { Line } from 'vue-chartjs'
    export default {
        extends: Line,
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
        },
        mounted () {
            axios.get(this.url)
                .then((res) => {
                    // Assign lables and data
                    var data = {
                        'labels': res.data.labels,
                        'datasets': [],
                    };
                    Object.keys(res.data.datasets).forEach(function (key) {
                        data.datasets.push({
                            label: key,
                            data: res.data.datasets[key],
                        });
                    });

                    // Assign colors to datasets
                    for (var i = 0; i < data.datasets.length; i++) {
                        data.datasets[i].backgroundColor = '#' + colorPalette[i %  colorPalette.length];
                        data.datasets[i].borderColor = '#' + colorPalette[i %  colorPalette.length];
                        data.datasets[i].fill = false;
                    }

                    // Options
                    var options = {
                        title: {
                            display: true,
                            text: this.title,
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
                                    display: true,
                                },
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
                    };

                    // Add y-axis label
                    if (this.ylabel) {
                        options.scales.yAxes[0].scaleLabel= {
                            display: true,
                            labelString: this.ylabel,
                        };
                    }

                    // Render chart
                    this.renderChart(data, options);
            });
        }
    }
</script>