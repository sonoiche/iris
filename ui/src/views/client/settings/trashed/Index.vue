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
                                            <h3 class="fw-bolder m-0">Applicants Trash Box</h3>
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
import ModalEmail from '@/views/client/applicant/search/modals/ModalEmail.vue';
require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    components: {
        ModalEmail
    },
    setup() {
        const swal = inject('$swal');
        const router = useRouter();
        const { status, returnApplicant, permanentDestroyApplicant } = applicantRepo();
        const state = reactive({
            placeholder: '',
            search_table: '',
            isLoading: true,
            idToRetrieve: 0,
            activeApplcantId: ''
        });
        const initialize = ref(false);
        const totalCount = ref(0);
        const modalActive = ref(false);

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#applicants-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/applicants/trashed`,
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
                            if(isCanDelete('Applicant Trashbox')) {
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
                                        <a href="javascript:;" class="menu-link px-3 remove-applicant">Retrieve Applicant</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 permanent-delete">Permanently Deleted</a>
                                    </div>
                                </div>
                                `
                            } else {
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
                                        <a href="javascript:;" class="menu-link px-3 remove-applicant">Retrieve Applicant</a>
                                    </div>
                                </div>
                                `
                            }
                        }
                    }
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

        const retrieveApplicant = (evt) => {
            state.idToRetrieve = evt.id;
            swal({
                title: 'Are you sure?',
                text: "You want to retrieve this?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    await returnApplicant(state.idToRetrieve);
                    if(status.value == 200) {
                        $('#applicants-table').DataTable().destroy();
                        initDatatable();
                    }
                }
            });
        }

        const deleteApplicant = (evt) => {
            state.idToRetrieve = evt.id;
            swal({
                title: 'Are you sure?',
                text: "You want to permanently delete this?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    await permanentDestroyApplicant(state.idToRetrieve);
                    if(status.value == 200) {
                        $('#applicants-table').DataTable().destroy();
                        initDatatable();
                    }
                }
            });
        }

        const isCanDelete = (name) => {
            if(page.authuser.role_id != 1) {
                let permissions = page.authuser.role?.permissions;
                let array_permission = false;
                permissions.forEach(item => {
                    if(item.name == name) {
                        array_permission = item.can_delete == 1;
                    }
                });

                return array_permission;
            }

            return true;
        }

        onMounted(() => {
            initialize.value = false;
            state.search_table = '';
            $('#applicants-table').DataTable().destroy();
            initDatatable();

            $('tbody', '#applicants-table').on( 'click', '.remove-applicant', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                retrieveApplicant(cell.data());
            });
            
            $('tbody', '#applicants-table').on( 'click', '.permanent-delete', function(){
                const cell =  $('#applicants-table').DataTable().cell( $(this).closest("td") );
                deleteApplicant(cell.data());
            });
        });

        return {
            state,
            initialize,
            totalCount,
            initDatatable,
            retrieveApplicant,
            viewApplicant,
            deleteApplicant,
            permanentDestroyApplicant,
            returnApplicant,
            isCanDelete
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