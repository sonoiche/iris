<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Applicant Source {{ source.name }}</h1>
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
                            <th class="bordered">Applicant Name</th>
                            <th class="bordered">Mobile No.</th>
                            <th class="bordered">Email Address</th>
                            <th class="bordered">Date Applied</th>
                            <th class="bordered">Status</th>
                            <th class="bordered">Position Applied</th>
                            <th class="bordered">Encoder</th>
                            <th class="bordered">Source</th>
                            <th class="bordered">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(applicant, index) in applicants" :key="index">
                            <td class="bordered text-center">{{ index+1 }}</td>
                            <td class="bordered">{{ applicant.fullname }}</td>
                            <td class="bordered">{{ applicant.mobile_number }}</td>
                            <td class="bordered">{{ applicant.email }}</td>
                            <td class="bordered">{{ applicant.date_applied }}</td>
                            <td class="bordered">{{ applicant.status }}</td>
                            <td class="bordered">{{ applicant.position_applied }}</td>
                            <td class="bordered">{{ applicant.encoder }}</td>
                            <td class="bordered">{{ applicant.source_name }}</td>
                            <td class="bordered">{{ applicant.updated_at }}</td>
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
            formData: JSON.parse(localStorage.getItem('source-applicants')),
            from: '',
            to: ''
        });
        const source = ref([]);
        const applicants = ref([]);
        const source_id = ref(route.params.id);

        onMounted( async () => {
            let formData = new FormData();
            formData.append('source_id', source_id.value ?? state.formData.source_id);
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/applicant-sources`, formData);
            applicants.value = response.data.data;
            source.value = response.data.source;
            state.from = response.data.from;
            state.to = response.data.to;
        });

        return {
            state,
            source,
            applicants,
            source_id
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