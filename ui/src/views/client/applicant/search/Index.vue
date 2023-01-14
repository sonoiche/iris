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
                                    <div class="card-header border-0 d-flex justify-content-between">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Search Applicant</h3>
                                        </div>
                                        <div class="d-flex align-items-center mr-3" style="width: 30%;">
                                            <div class="d-flex align-items-center position-relative my-1" style="width: 100%">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                        <path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                </span>
                                                <input type="text" class="form-control form-control-solid ps-14" style="width: 100%" v-model="state.search_table" @keyup="searchApplicant" :placeholder="state.placeholder" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <table class="table table-striped table-hover w-100" id="applicants-table">
                                                <thead>
                                                    <tr>
                                                        <th class="fw-bolder text-center">#</th>
                                                        <th class="fw-bolder">Applicant Name</th>
                                                        <th class="fw-bolder">Contacts</th>
                                                        <th class="fw-bolder">Position Applied</th>
                                                        <th class="fw-bolder">Date Applied</th>
                                                        <th class="fw-bolder">Status</th>
                                                        <th class="fw-bolder">Latest Remarks</th>
                                                        <th class="fw-bolder text-center">Action</th>
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
import { onMounted, reactive, ref, inject } from 'vue';
import _debounce from 'lodash/debounce';
import $ from 'jquery';
import { useRouter } from 'vue-router';
import applicantRepo from '@/repositories/applicants/applicant';
require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup() {
        const swal = inject('$swal');
        const router = useRouter();
        const { status, destroyApplicant } = applicantRepo();
        const state = reactive({
            placeholder: '',
            search_table: '',
            isLoading: true,
            idToDelete: 0
        });
        const initialize = ref(false);
        const totalCount = ref(0);

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#applicants-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/applicants/datatable`,
                    type: 'POST',
                    data: {
                        search: result.search ?? ''
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
                    { 'data': 'applicant_name', searchable: false, orderable: true,
                        render: function(data, type, row, meta) {
                            return `<a href="javascript:;" class="view-applicant">${data['applicant_name']}</a>`;
                        }
                    },
                    { 'data': 'mobile_number', searchable: false, orderable: true },
                    { 'data': 'position_applied', searchable: false, orderable: true },
                    { 'data': 'date_applied_display', searchable: false, orderable: true },
                    { 'data': 'status', searchable: false, orderable: true },
                    { 'data': 'latest_remarks', searchable: false, orderable: true },
                    { 'data': 'action', searchable: false, orderable: false, className: "text-center",
                        render: function(data, type, row, meta) {
                            return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                            <div class="dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-180px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 send-email" style="display: none">Send Email</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 change-status" style="display: none">Change Lineup Status</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 edit-applicant">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 remove-applicant">Delete</a>
                                </div>
                            </div>
                            `
                        }
                    }
                ]
            });
        }

        const searchApplicant = _debounce( function () {
            initialize.value = false;
            $('#applicants-table').DataTable().destroy();
            let data = { search: state.search_table };
            initDatatable(data);
        }, 500);

        const editApplicant = async (id) => {
            initialize.value = false;
            router.push({
                name: 'client.applicant.edit',
                params: {
                    id: id
                }
            });
        }

        const removeApplicant = (id) => {
            state.idToDelete = id;
            swal({
                title: 'Are you sure?',
                text: "You want to delete this?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    await destroyApplicant(state.idToDelete);
                    if(status.value == 200) {
                        $('#applicants-table').DataTable().destroy();
                        initDatatable();
                    }
                }
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

        const sendEmail = () => {

        }

        const changeStatus = () => {

        }

        onMounted(() => {
            initialize.value = false;
            state.search_table = '';
            $('#applicants-table').DataTable().destroy();
            initDatatable();
            $('tbody', '#applicants-table').on( 'click', '.view-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                viewApplicant(cell.data()['applicant_id']);
            });

            $('tbody', '#applicants-table').on( 'click', '.send-email', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                sendEmail(cell.data());
            });

            $('tbody', '#applicants-table').on( 'click', '.change-status', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                changeStatus(cell.data());
            });

            $('tbody', '#applicants-table').on( 'click', '.edit-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                editApplicant(cell.data()['applicant_id']);
            });

            $('tbody', '#applicants-table').on( 'click', '.remove-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                removeApplicant(cell.data());
            });
        });

        return {
            state,
            initialize,
            totalCount,
            initDatatable,
            searchApplicant,
            editApplicant,
            removeApplicant,
            viewApplicant,
            sendEmail,
            changeStatus
        }
    },
}
</script>

<style>
.w-180px {
    width: 180px !important;
}
.paginate_button {
    cursor: pointer;
}
</style>