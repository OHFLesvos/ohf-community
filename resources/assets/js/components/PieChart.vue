<script>
    var palette = require('google-palette');
    var colorPalette = palette('tol', 12);

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
                            backgroundColor: Array(colorPalette.length).fill().map((e, i) => '#' + colorPalette[i %  colorPalette.length]),
                        });
                    });

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