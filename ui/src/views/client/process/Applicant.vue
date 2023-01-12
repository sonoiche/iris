<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 90%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title d-flex justify-content-between" style="width: 100%">
                                            <div class="d-flex align-items-center">
                                                <h3 class="fw-bolder m-0">Applicant Monitoring</h3>
                                            </div>
                                            <div class="d-flex align-items-center" style="width: 25%;">
                                                <BaseSelect 
                                                    :options="principals"
                                                    :placeholder="`Select Principal`"
                                                    :id="`principal_id`"
                                                    @select-value="setPrincipal"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="applicants-table">
                                                <thead>
                                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                        <th class="w-10px pe-2" rowspan="2">
                                                            <div class="process-head">#</div>
                                                        </th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Principal Name</div>
                                                        </th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Applicant Name</div>
                                                        </th>
                                                        <th rowspan="1" colspan="7" class="text-center">Manpower Details</th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Deployed By</div>
                                                        </th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Deployed Date</div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="1">Actual Employer</th>
                                                        <th rowspan="1">Agreed Salary</th>
                                                        <th rowspan="1">Direct Hire</th>
                                                        <th rowspan="1">Worksite</th>
                                                        <th rowspan="1">Country</th>
                                                        <th rowspan="1">Job Order Number</th>
                                                        <th rowspan="1">Endorsement Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-bold"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import $ from 'jquery';
import principalRepo from '@/repositories/employer/principal';

require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup(props) {
        const router = useRouter();
        const state = reactive({
            principal_id: '',
            isLoading: true
        });
        const { principals, getSelectPrincipal } = principalRepo();
        
        const initialize = ref(false);
        const totalCount = ref(0);

        const setPrincipal = (value) => {
            state.principal_id = value.id;
            initialize.value = false;
            $('#applicants-table').DataTable().destroy();
            initDatatable();
        }

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#applicants-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/process/applicants/datatable`,
                    type: 'POST',
                    data: {
                        principal_id: state.principal_id ?? ''
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", `Bearer ${localStorage.getItem('token')}`);
                    }
                },
                drawCallback: function(response) {
                    totalCount.value = response._iRecordsTotal;
                    state.isLoading = false;
                },
                "pageLength": 30,
                "searching": false,
                "lengthChange": false,
                'columns': [
                    { 'data': 'counter', searchable: false, orderable: false, className: "text-center" },
                    { 'data': 'principal_name', searchable: false, orderable: false },
                    { 'data': 'applicant_name', searchable: false, orderable: true,
                        render: function(data, type, row, meta) {
                            return `<a href="javascript:;" class="view-applicant">${data['applicant_name']}</a>`;
                        }
                    },
                    { 'data': 'actual_employer', searchable: false, orderable: false },
                    { 'data': 'agreed_salary', searchable: false, orderable: false },
                    { 'data': 'direct_hire', searchable: false, orderable: false, className: "text-center" },
                    { 'data': 'worksite', searchable: false, orderable: false },
                    { 'data': 'country', searchable: false, orderable: false },
                    { 'data': 'job_order_no', searchable: false, orderable: false },
                    { 'data': 'endorsement_date', searchable: false, orderable: false },
                    { 'data': 'deployed_by', searchable: false, orderable: false },
                    { 'data': 'deployed_date', searchable: false, orderable: false }
                ]
            });
        }

        const viewApplicant = (id) => {
            initialize.value = false;
            $('#applicants-table').DataTable().destroy();
            router.push({
                name: 'client.applicant.show',
                params: {
                    id: id
                }
            });
        }

        onMounted( async () => {
            await getSelectPrincipal();
            initDatatable();

            $('tbody', '#applicants-table').on( 'click', '.view-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                viewApplicant(cell.data()['applicant_id']);
            });
        });

        return {
            state,
            principals,
            getSelectPrincipal,
            initialize,
            totalCount,
            initDatatable,
            setPrincipal,
            viewApplicant
        }
    }
}
</script>

<style scoped>
.process-head {
    display: flex;
    align-items: center;
    height: 75px;
}
</style>