<template>
    <div>
        <highcharts :options="chartOptions" ref="chart"></highcharts>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import statusRepo from '@/repositories/settings/status';

export default {
    setup(props) {
        const { seriesStatus, arrayStatus, getStatuses } = statusRepo();
        const chartOptions = ref([]);

        onMounted( async () => {
            await getStatuses();
            chartOptions.value = {
                chart: {
                    chartWidth: '100%'
                },
                title: {
                    text: 'Applicant Status Graph'
                },
                xAxis: {
                    categories: arrayStatus.value
                },
                yAxis: {
                    title: {
                        text: 'Status Graph'
                    },
                    allowDecimals: false
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    name: 'Encoded Applicant',
                    data: seriesStatus.value
                }],
                responsive: {
                    rules: [{
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            }
        });

        return {
            chartOptions
        }
    }
}
</script>