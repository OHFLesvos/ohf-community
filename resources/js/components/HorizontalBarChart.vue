<script>
    var palette = require('google-palette');
    var colorPalette = palette('tol', 12);

    import { HorizontalBar } from 'vue-chartjs'
    export default {
        extends: HorizontalBar,
        props: {
            title: {
                type: String,
                required: true
            },
            url: {
                type: String,
                required: true
            },
            legend: {
                type: Boolean,
                required: false,
                default: true
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
                    var sum = 0;
                    Object.keys(res.data.datasets).forEach(function (key) {
                        data.datasets.push({
                            label: key,
                            data: res.data.datasets[key],
                        });
                        sum += res.data.datasets[key].reduce(function(a, b) { return a + b; }, 0);
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
                            display: this.legend,
                            position: 'bottom'
                        },
                        tooltips: {
                            enabled: false
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
                        },
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    display: false,
                                    max: sum, // + Math.ceil(sum * 0.03),
                                },
                                gridLines : {
                                    display : false,
                                    drawBorder: false,
                                }
                            }],
                            yAxes: [{
                                stacked: true, 
                                gridLines : {
                                    display : false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    display: false,
                                },
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 0
                        },
                    };

                    // Render chart
                    this.renderChart(data, options);
            });
        }
    }
</script>