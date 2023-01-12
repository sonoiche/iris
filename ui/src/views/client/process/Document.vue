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
                                                <h3 class="fw-bolder m-0">Document Monitoring</h3>
                                            </div>
                                            <div class="d-flex justify-content-end" style="width: 70%;">
                                                <div class="d-flex align-items-center" style="width: 20%; margin-right: 10px;">
                                                    <BaseSelect
                                                        :placeholder="`Enter Status`"
                                                        :id="`status`"
                                                        :options="statusOptions"
                                                        :marginBottomOn="false"
                                                        @select-value="setStatus"
                                                    />
                                                </div>
                                                <div class="d-flex align-items-center" style="width: 25%; margin-right: 10px;">
                                                    <BaseSelect 
                                                        :options="principals"
                                                        :placeholder="`Select Principal`"
                                                        :id="`principal_id`"
                                                        :marginBottomOn="false"
                                                        @select-value="setPrincipal"
                                                    />
                                                </div>
                                                <div class="d-flex align-items-center" style="width: 25%; margin-right: 10px;">
                                                    <BaseSelect 
                                                        :options="joborderOptions"
                                                        :placeholder="`Select Job Order`"
                                                        :id="`job_order_id`"
                                                        :marginBottomOn="false"
                                                        @select-value="setJobOrder"
                                                    />
                                                </div>
                                                <div class="d-flex align-items-center" style="width: 25%; margin-right: 10px;">
                                                    <BaseSelect 
                                                        :options="positionOptions"
                                                        :placeholder="`Select Job Order Position`"
                                                        :id="`position_id`"
                                                        :marginBottomOn="false"
                                                        @select-value="setPosition"
                                                    />
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-primary" @click="saveChanges">Submit</button>
                                                </div>
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
                                                        <th rowspan="2">
                                                            <div class="process-head">Job Order Number</div>
                                                        </th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Position</div>
                                                        </th>
                                                        <th rowspan="2">
                                                            <div class="process-head">Status</div>
                                                        </th>
                                                        <th rowspan="1" colspan="5" class="text-center">Document Details</th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="1">Document Name</th>
                                                        <th rowspan="1">Type</th>
                                                        <th rowspan="1">Date Issued</th>
                                                        <th rowspan="1">Expiry Date</th>
                                                        <th rowspan="1">Submitted Date</th>
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
import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import $ from 'jquery';
import principalRepo from '@/repositories/employer/principal';
import statusRepo from '@/repositories/settings/status';
import joborderRepo from '@/repositories/employer/joborder';
import positionRepo from '@/repositories/employer/position';

require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup() {
        const router = useRouter()
        const state = reactive({
            status_id : '',
            principal_id: '',
            job_order_id: '',
            position_id: ''
        });
        const { results, getStatuses } = statusRepo();
        const { principals, getSelectPrincipal } = principalRepo();
        const { positions, getPositionByJobOrder } = positionRepo();
        const { joborders, getJobOrdersByPrincipal } = joborderRepo();

        const isSuccess  = ref(true);
        const initialize = ref(false);
        const totalCount = ref(0);

        const statusOptions = computed(() => {
            const arr_status = [];
            results.value.forEach(item => {
                arr_status.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_status;
        });

        const joborderOptions = computed(() => {
            const arr_joborder = [];
            joborders.value.forEach(item => {
                arr_joborder.push({
                    id: item.id,
                    name: item.job_order_number
                });
            });

            return arr_joborder;
        });

        const positionOptions = computed(() => {
            const arr_position = [];
            positions.value.forEach(item => {
                arr_position.push({
                    id: item.id,
                    name: item.position_title
                });
            });

            return arr_position;
        });

        const setStatus = (value) => {
            state.status_id = value.id;
        }

        const setPrincipal = async (value) => {
            state.principal_id = value.id;
            await getJobOrdersByPrincipal(state.principal_id);
        }

        const setJobOrder = async (value) => {
            state.job_order_id = value.id;
            await getPositionByJobOrder(state.job_order_id);
        }

        const setPosition = (value) => {
            state.position_id = value.id;
        }

        const saveChanges = () => {
            isSuccess.value = true;
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
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/process/documents/datatable`,
                    type: 'POST',
                    data: {
                        status_id: state.status_id ?? '',
                        principal_id: state.principal_id ?? '',
                        job_order_id: state.job_order_id ?? '',
                        position_id: state.position_id ?? ''
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
                    { 'data': 'job_order_no', searchable: false, orderable: false },
                    { 'data': 'position_title', searchable: false, orderable: false },
                    { 'data': 'status', searchable: false, orderable: false },
                    { 'data': 'document_name', searchable: false, orderable: false },
                    { 'data': 'document_type', searchable: false, orderable: false },
                    { 'data': 'date_issued', searchable: false, orderable: false },
                    { 'data': 'expiry_date', searchable: false, orderable: false },
                    { 'data': 'submitted_date', searchable: false, orderable: false }
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
            await getStatuses();
            await getSelectPrincipal();

            $('tbody', '#applicants-table').on( 'click', '.view-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                viewApplicant(cell.data()['applicant_id']);
            });
        });

        return {
            state,
            results,
            getStatuses,
            statusOptions,
            principals,
            getSelectPrincipal,
            positions,
            positionOptions,
            getPositionByJobOrder,
            joborders,
            getJobOrdersByPrincipal,
            joborderOptions,
            setStatus,
            setJobOrder,
            setPrincipal,
            setPosition,
            initialize,
            totalCount,
            initDatatable,
            viewApplicant,
            isSuccess,
            saveChanges
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