<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Audit Trail Report by {{ state.report_name }}</h1>
            <p>From: {{ state.from }} - {{ state.to }}</p>
        </div>
        <div class="mt-3" style="width: 90%; float: right; margin-right: 70px;">
            <div class="d-flex justify-content-between mb-6">
                <h3>Total Results Found: {{ results.length }}</h3>
                <div>
                    <button class="btn btn-success">Export to Excel</button>
                </div>
            </div>
            <div class="row mb-6">
                <div v-if="state.formData.report_type == 'access'">
                    <table class="table align-middle fs-6 gy-5 bordered">
                        <thead>
                            <tr>
                                <th class="bordered text-center">#</th>
                                <th class="bordered">Date/Time</th>
                                <th class="bordered">Name of User</th>
                                <th class="bordered">Email Address</th>
                                <th class="bordered">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(result, index) in results" :key="index">
                                <td class="bordered text-center">{{ index+1 }}</td>
                                <td class="bordered">{{ result.created_at_display }}</td>
                                <td class="bordered">{{ result.username }}</td>
                                <td class="bordered">{{ result.user?.email }}</td>
                                <td class="bordered">{{ result.ip_address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <table class="table align-middle fs-6 gy-5 bordered">
                        <thead>
                            <tr>
                                <th class="bordered text-center">#</th>
                                <th class="bordered">Date/Time</th>
                                <th class="bordered">Applicant Number</th>
                                <th class="bordered">Applicant Name</th>
                                <th class="bordered">Module</th>
                                <th class="bordered">Action</th>
                                <th class="bordered">Name of User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(result, index) in results" :key="index">
                                <td class="bordered text-center">{{ index+1 }}</td>
                                <td class="bordered">{{ result.created_at_display }}</td>
                                <td class="bordered">{{ result.applicant_id }}</td>
                                <td class="bordered">{{ result.applicant_name }}</td>
                                <td class="bordered">{{ result.module }}</td>
                                <td class="bordered">{{ result.user_action }}</td>
                                <td class="bordered">{{ result.username }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                user_id: route.query.user_id,
                report_type: route.query.report_type,
                from: route.query.from,
                to: route.query.to
            },
            from: '',
            to: '',
            report_name: ''
        });
        const results = ref([]);

        onMounted( async () => {
            let formData = new FormData();
            formData.append('user_id', state.formData.user_id ?? '');
            formData.append('activity_type', state.formData.report_type ?? '');
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/audit-trail`, formData);
            results.value = response.data.data;
            state.report_name = (state.formData.report_type == 'access') ? 'User Access Log' : 'Create, Update Activity of Applicant Log';
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