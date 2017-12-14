<script>
    import { Line } from 'vue-chartjs'
    export default {
        extends: Line,
        props: [ 'data' ],
        mounted () {
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