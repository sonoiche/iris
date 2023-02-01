<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Applicant Status Lists</h1>
            <p>From: {{ state.from }} - {{ state.to }}</p>
        </div>
        <div class="mt-3 mx-auto" style="width: 40%;">
            <div class="d-flex justify-content-between">
                <h3>Total Results Found: {{ results.length }}</h3>
            </div>
            <div class="row mb-6">
                <table class="table align-middle fs-6 gy-5 bordered">
                    <thead>
                        <tr>
                            <th class="bordered text-center">#</th>
                            <th class="bordered">Status</th>
                            <th class="bordered text-center">Number of Applicants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(status, index) in results" :key="index">
                            <td class="bordered text-center" style="width: 5%">{{ index+1 }}</td>
                            <td class="bordered" style="width: 70%">{{ status.status_name }}</td>
                            <td class="bordered text-center" style="width: 25%"><div v-html="status.applicant_count"></div></td>
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
        const route = useRoute();
        const state = reactive({
            formData: {
                status_id: route.query.status_id,
                from: route.query.from,
                to: route.query.to
            },
            from: '',
            to: ''
        });
        const results = ref([]);

        onMounted( async () => {
            let formData = new FormData();
            formData.append('status_id', state.formData.status_id ?? '');
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/applicant-status`, formData);
            results.value = response.data.data;
            state.from = response.data.from;
            state.to = response.data.to;
        });

        return {
            state,
            results
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