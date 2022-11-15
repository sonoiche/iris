<template>
    <div>
        <Header :title="`Applicant Status`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <div class="card mb-5 mb-xl-12">
                            <div class="card-header border-0 d-flex justify-content-between">
                                <div class="card-title">
                                    <h3 class="fw-bolder m-0">Applicant Status</h3>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-primary btn-sm" @click="addStatus">Add Status</button>
                                </div>
                            </div>
                            <div class="card-body border-top p-9">
                                <div class="d-flex justify-content-end">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                                <path
                                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                    fill="currentColor"
                                                ></path>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control form-control-solid w-250px ps-14" v-model="page.search_table" @keyup="searchDatatable" placeholder="Search applicant status" />
                                    </div>
                                </div>
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="status-table">
                                    <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2">#</th>
                                            <th class="min-w-125px">Name</th>
                                            <th class="text-end min-w-100px">Actions</th>
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
        <ModalStatus :is-active="modalActive" :status="result" :isLoading="page.isLoading" @close-modal="closeModalStatus" @refresh-table="refresh" />
    </div>
</template>

<script>
import { ref, onMounted, reactive, inject } from '@vue/runtime-core';
import $ from 'jquery';
import _debounce from 'lodash/debounce';
import statusRepo from '@/repositories/settings/status';
import ModalStatus from '@/views/client/settings/status/modals/Index.vue';
require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup() {
        const swal = inject('$swal');
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            search_table: '',
            isLoading: true,
            idToDelete: ''
        });
        const initialize = ref(false);
        const totalCount = ref(0);
        const modalActive = ref(false);

        const { status, result, getStatus, destroyStatus } = statusRepo();

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#status-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/status/datatable`,
                    type: 'POST',
                    data: {
                        search: result.search ?? '',
                        agency_id: page.authuser.agency_id
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", `Bearer ${localStorage.getItem('token')}`);
                    }
                },
                drawCallback: function(response) {
                    totalCount.value = response._iRecordsTotal;
                },
                "pageLength": 30,
                "searching": false,
                "lengthChange": false,
                'columns': [
                    { 'data': 'counter', searchable: false, orderable: false, className: "text-center" },
                    { 'data': 'name', searchable: true, orderable: true },
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
                            <div class="dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 edit-status">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 remove-status">Delete</a>
                                </div>
                            </div>
                            `
                        }
                    }
                ]
            });
        }

        const searchDatatable = _debounce( function () {
            let data = { search: page.search_table };
            $('#status-table').DataTable().destroy();
            initDatatable(data);
        }, 500);

        const addStatus = () => {
            modalActive.value = true;
            page.isLoading = false;
            result.value = [];
        }

        const editStatus = async (id) => {
            modalActive.value = true;
            await getStatus(id);
            page.isLoading = false;
        }

        const removeStatus = (id) => {
            page.idToDelete = id;
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
                    await destroyStatus(page.idToDelete);
                    if(status.value == 200) {
                        $('#status-table').DataTable().destroy();
                        initDatatable();
                    }
                }
            });
        }

        const closeModalStatus = () => {
            modalActive.value = false;
            page.isLoading = true;
        }

        const refresh = () => {
            $('#status-table').DataTable().destroy();
            initDatatable();
        }

        onMounted(() => {
            initDatatable();

            $('tbody', '#status-table').on( 'click', '.edit-status', function(){
                const cell =  $('#status-table').DataTable().cell( $(this).closest("td") );
                editStatus(cell.data());
            });

            $('tbody', '#status-table').on( 'click', '.remove-status', function(){
                const cell =  $('#status-table').DataTable().cell( $(this).closest("td") );
                removeStatus(cell.data());
            });
        });

        return {
            page,
            initialize,
            totalCount,
            modalActive,
            initDatatable,
            searchDatatable,
            addStatus,
            closeModalStatus,
            refresh,
            status,
            result,
            getStatus,
            destroyStatus
        }
    },
    components: {
        ModalStatus
    }
}
</script>