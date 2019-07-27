<script>
    import Chart from 'chart.js'
    import 'chartjs-plugin-colorschemes';
    import { ClassicRedGreen11 } from 'chartjs-plugin-colorschemes/src/colorschemes/colorschemes.tableau';
    import {Line, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins;

    export default {
        name: "LineChart",
        extends: Line,
        props: {
            options: {
                default: () => {
                }, type: [Object]
            },
        },
        mixins: [reactiveProp],
        mounted() {
            Chart.defaults.global.plugins.colorschemes.schema = ClassicRedGreen11;
            this.renderChart(this.chartData, this.options)
        }
    }
</script>

<style scoped>

</style>