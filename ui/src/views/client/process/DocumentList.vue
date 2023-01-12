<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container" :class="{ 'container-xxl': !isGenerated, 'mx-auto w-80p': isGenerated }">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title w-full">
                                            <div class="d-flex justify-content-between w-full">
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fw-bolder m-0">Document List</h3>
                                                </div>
                                                <div class="d-flex align-items-center" v-if="isGenerated">
                                                    <button class="btn btn-outline-success btn-sm" @click="resetFilter">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9" v-if="!isGenerated">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Document Type"
                                                            :options="document_types"
                                                            :placeholder="`All Document Type`"
                                                            id="document_type"
                                                            @select-value="setDocumentType"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <label for="job_order_type" class="form-label fs-6 fw-bolder mb-3">Filter By</label>
                                                        <div class="d-flex align-items-center" style="height: 40px;">
                                                            <div class="form-check form-check-custom form-check-solid mr-15">
                                                                <input class="form-check-input" type="radio" v-model="state.filter_by" value="submitted" id="submitted"/>
                                                                <label class="form-check-label" for="submitted">
                                                                    Document Submitted
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="radio" v-model="state.filter_by" value="expiration" id="expiration"/>
                                                                <label class="form-check-label" for="expiration">
                                                                    Document Expiration
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                                            <label class="form-label fs-6 fw-bolder mb-3">Date</label>
                                                            <date-picker 
                                                                v-model="state.date"
                                                                format="MM/dd/yyyy"
                                                                inputClassName="form-control form-control-solid fc-calendar"
                                                                range multi-calendars
                                                            ></date-picker>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-primary" @click="generateReport">Create</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body border-top p-9" v-show="isGenerated">
                                            <loading v-if="state.isLoading" />
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="applicants-table" v-show="!state.isLoading">
                                                <thead>
                                                    <tr>
                                                        <th class="w-10px pe-2">#</th>
                                                        <th class="min-w-125px">Date Submitted</th>
                                                        <th class="min-w-125px">Applicant Number</th>
                                                        <th class="min-w-125px">Applicant Name</th>
                                                        <th class="min-w-125px">Document</th>
                                                        <th class="min-w-125px">Attachment</th>
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
import documentTypeRepo from '@/repositories/settings/document_type';

require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup(props) {
        const router = useRouter();
        const state = reactive({
            document_type_id: '',
            filter_by: 'submitted',
            date: '',
            isLoading: true
        });
        const { documents, getDocuments } = documentTypeRepo();
        const isGenerated = ref(false);
        const initialize = ref(false);
        const totalCount = ref(0);

        const document_types = computed(() => {
            const arr_types = [];
            documents.value.forEach(item => {
                arr_types.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_types;
        });

        const setDocumentType = (value) => {
            state.document_type_id = value.id;
        }

        const generateReport = () => {
            isGenerated.value = true;
            initialize.value = false;
            $('#applicants-table').DataTable().destroy();

            setTimeout(() => {
                state.isLoading = false;
            }, 3000);
            initDatatable();
        }

        const resetFilter = () => {
            state.document_type_id = '';
            state.filter_by = 'submitted';
            const startDate = new Date();
            const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
            state.date = [startDate, endDate];

            isGenerated.value = false;
        }

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            
            $('#applicants-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/reports/documents-datatable`,
                    type: 'POST',
                    data: {
                        document_type_id: state.document_type_id ?? '',
                        filter_by: state.filter_by ?? '',
                        from: (state.date) ? new Date(state.date[0]).toISOString() : '',
                        to: (state.date) ? new Date(state.date[1]).toISOString() : ''
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
                    { 'data': 'date_submitted', searchable: false, orderable: true },
                    { 'data': 'applicant_number', searchable: false, orderable: false,
                        render: function(data, type, row, meta) {
                            return `<a href="javascript:;" class="view-applicant">${data['applicant_id']}</a>`;
                        }
                    },
                    { 'data': 'applicant_name', searchable: false, orderable: false,
                        render: function(data, type, row, meta) {
                            return `<a href="javascript:;" class="view-applicant">${data['applicant_name']}</a>`;
                        }
                    },
                    { 'data': 'document_type', searchable: false, orderable: false },
                    { 'data': 'attachment', searchable: false, orderable: false, className: "text-center" }
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
            const startDate = new Date();
            const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
            state.date = [startDate, endDate];

            await getDocuments();

            $('tbody', '#applicants-table').on( 'click', '.view-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                viewApplicant(cell.data()['applicant_id']);
            });
        });

        return {
            state,
            documents,
            getDocuments,
            document_types,
            isGenerated,
            initialize,
            totalCount,
            setDocumentType,
            generateReport,
            resetFilter,
            initDatatable,
            viewApplicant
        }
    }
}
</script>

<style scoped>
.mr-15 {
    margin-right: 15px !important;
}
.w-80p {
    width: 80% !important;
}
</style>