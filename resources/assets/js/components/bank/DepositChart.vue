<script>
    var palette = require('google-palette');
    var colorPalette = palette('tol', 8);

    import { Line } from 'vue-chartjs'
    export default {
        extends: Line,
        props: [ 'data' ],
        mounted () {
            // Assign colors to datasets
            for (var i = 0; i < this.data.datasets.length; i++) {
                this.data.datasets[i].backgroundColor = '#' + colorPalette[i %  colorPalette.length];
                this.data.datasets[i].borderColor = '#' + colorPalette[i %  colorPalette.length];
                this.data.datasets[i].fill = false;
            }
            // Render chart
            this.renderChart(this.data, {
                title: {
                    display: true,
                    text: 'Deposits per project'
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
                responsive: true,
                maintainAspectRatio: false,
            })
        }
    }
</script>