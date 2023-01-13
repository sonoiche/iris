<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Interview Calendar Report</h1>
            <p>From: {{ state.from }} - {{ state.to }}</p>
        </div>
        <div class="mt-3" style="width: 90%; float: right; margin-right: 70px;">
            <div class="d-flex justify-content-between">
                <h3>Total Results Found: {{ joborders.length }}</h3>
                <div>
                    <button class="btn btn-success">Export to Excel</button>
                </div>
            </div>
            <div class="row mb-6">
                <table class="table align-middle fs-6 gy-5 bordered">
                    <thead>
                        <tr>
                            <th class="bordered text-center">#</th>
                            <th class="bordered">Date Created</th>
                            <th class="bordered">Principal</th>
                            <th class="bordered">Manpower Request</th>
                            <th class="bordered">Status</th>
                            <th class="bordered">User</th>
                            <th class="bordered">Position(s)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(joborder, index) in joborders" :key="index">
                            <td class="bordered text-center">{{ index+1 }}</td>
                            <td class="bordered">{{ joborder.created_at }}</td>
                            <td class="bordered">{{ joborder.principal }}</td>
                            <td class="bordered">{{ joborder.job_order }}</td>
                            <td class="bordered">{{ joborder.status }}</td>
                            <td class="bordered">{{ joborder.fullname }}</td>
                            <td class="bordered">{{ joborder.position_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
    setup(props) {
        const route = useRoute()
        const state = reactive({
            formData: {
                principal_id: route.query.principal_id,
                job_order_id: route.query.job_order_id,
                from: route.query.from,
                to: route.query.to
            },
            from: '',
            to: ''
        });
        const joborders = ref([]);

        onMounted( async () => {
            let formData = new FormData();
            formData.append('principal_id', state.formData.principal_id ?? '');
            formData.append('job_order_id', state.formData.job_order_id ?? '');
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/manpower`, formData);
            joborders.value = response.data.data;
            state.from = response.data.from;
            state.to = response.data.to;
        });

        return {
            state,
            joborders
        }
    }
}
</script>

<style scoped>
.bordered {
    border: 1px solid #ccc;
    padding: 5px 7px;
}
.table.gy-5 th, .table.gy-5 td {
    padding-top: 7px;
    padding-bottom: 7px;
}
</style>