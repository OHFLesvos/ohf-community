<script>
import palette from 'google-palette'
const colorPalette = palette('tol', 12);
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
                var chartData = {
                    'labels': res.data.labels,
                    'datasets': [],
                };
                Object.keys(res.data.datasets).forEach(function (key) {
                    chartData.datasets.push({
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
                    // Percentage in tooltips (see https://stackoverflow.com/questions/37257034/chart-js-2-0-doughnut-tooltip-percentages/49717859#49717859)
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                                var total = meta.total;
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = parseFloat((currentValue/total*100).toFixed(1));
                                return currentValue + ' (' + percentage + '%)';
                            },
                            title: function(tooltipItem, data) {
                                return data.labels[tooltipItem[0].index];
                            }
                        }
                    },
                };

                // Render chart
                this.renderChart(chartData, options);
        });
    }
}
</script>
