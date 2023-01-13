<template>
    <div class="mt-8">
        <div class="text-center">
            <h1>Applicant Source Lists</h1>
            <p>From: {{ state.from }} - {{ state.to }}</p>
        </div>
        <div class="mt-3 mx-auto" style="width: 40%;">
            <div class="d-flex justify-content-between">
                <h3>Total Results Found: {{ sources.length }}</h3>
            </div>
            <div class="row mb-6">
                <table class="table align-middle fs-6 gy-5 bordered">
                    <thead>
                        <tr>
                            <th class="bordered text-center">#</th>
                            <th class="bordered">Source</th>
                            <th class="bordered text-center">Number of Applicants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(source, index) in sources" :key="index">
                            <td class="bordered text-center" style="width: 5%">{{ index+1 }}</td>
                            <td class="bordered" style="width: 70%">{{ source.source_name }}</td>
                            <td class="bordered text-center" style="width: 25%"><div v-html="source.applicant_count"></div></td>
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
                source_id: route.query.source_id,
                from: route.query.from,
                to: route.query.to
            },
            from: '',
            to: ''
        });
        const sources = ref([]);

        onMounted( async () => {
            let formData = new FormData();
            formData.append('source_id', state.formData.source_id ?? '');
            formData.append('from', state.formData.from ?? '');
            formData.append('to', state.formData.to ?? '');

            let response =  await axios.post(`client/reports/applicant-sources`, formData);
            sources.value = response.data.data;
            state.from = response.data.from;
            state.to = response.data.to;
        });

        return {
            state,
            sources
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