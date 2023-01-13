<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Deployment Report by {{ principal_name }}</h1>
            <p>From: {{ state.from }} - {{ state.to }}</p>
        </div>
        <div class="mt-3" style="width: 90%; float: right; margin-right: 70px;">
            <div class="d-flex justify-content-between">
                <h3>Total Results Found: {{ applicants.length }}</h3>
                <div>
                    <button class="btn btn-success">Export to Excel</button>
                </div>
            </div>
            <div class="row mb-6">
                <table class="table align-middle fs-6 gy-5 bordered">
                    <thead>
                        <tr>
                            <th class="bordered text-center">#</th>
                            <th class="bordered">Deployment Date</th>
                            <th class="bordered">Applicant Name</th>
                            <th class="bordered">Email</th>
                            <th class="bordered">Manpower Request</th>
                            <th class="bordered">Destination</th>
                            <th class="bordered">Country</th>
                            <th class="bordered">Agreed Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(applicant, index) in applicants" :key="index">
                            <td class="bordered text-center">{{ index+1 }}</td>
                            <td class="bordered">{{ applicant.deployed_date }}</td>
                            <td class="bordered">{{ applicant.fullname }}</td>
                            <td class="bordered">{{ applicant.email }}</td>
                            <td class="bordered">{{ applicant.job_order }}</td>
                            <td class="bordered">{{ applicant.worksite }}</td>
                            <td class="bordered">{{ applicant.country }}</td>
                            <td class="bordered">{{ applicant.salary }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, onMounted, ref } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

export default {
    setup(props) {
        const route = useRoute();
        const state = reactive({
            formData: {
                principal_id: route.query.principal_id,
                from: route.query.from,
                to: route.query.to
            },
            from: '',
            to: ''
        });
        const applicants = ref([]);
        const principal_name = ref('');

        onMounted( async () => {
            let formData = new FormData();
            formData.append('principal_id', state.formData.principal_id ?? '');
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/deployment`, formData);
            applicants.value = response.data.data;
            state.from = response.data.from;
            state.to = response.data.to;
            principal_name.value = response.data.principal_name;
        });

        return {
            state,
            applicants,
            principal_name
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