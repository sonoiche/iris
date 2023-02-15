<template>
    <div>
        <Header :title="`Employer Manager`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 80%">
                        <div class="card mb-5 mb-xl-12">
                            <div class="card-header border-0 d-flex justify-content-between">
                                <div class="card-title">
                                    <h3 class="fw-bolder m-0">Employer Manager</h3>
                                </div>
                                <div class="d-flex align-items-center" v-if="isCanWrite('Employer Manager')">
                                    <router-link class="btn btn-primary btn-sm" :to="{ name: 'client.employer.create' }">Add Employer</router-link>
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
                                        <input type="text" class="form-control form-control-solid w-250px ps-14" v-model="page.search_table" @keyup="searchDatatable" placeholder="Search Employer Name" />
                                    </div>
                                </div>
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="employer-table">
                                    <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2">#</th>
                                            <th class="min-w-125px">Employer Name</th>
                                            <th class="min-w-125px">Contact Details</th>
                                            <th class="min-w-125px">Accreditation Details</th>
                                            <th class="min-w-125px">Main Contact Person</th>
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
    </div>
</template>

<script>
import { ref, onMounted, reactive, inject } from '@vue/runtime-core';
import $ from 'jquery';
import _debounce from 'lodash/debounce';
import principalRepo from '@/repositories/employer/principal';
import { useRouter } from 'vue-router';
require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    setup() {
        const router = useRouter();
        const swal = inject('$swal');
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            search_table: '',
            isLoading: true,
            idToDelete: ''
        });
        const initialize = ref(false);
        const totalCount = ref(0);

        const { status, destroyPrincipal } = principalRepo();

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#employer-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/principals/datatable`,
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
                    { 'data': 'contact_details', searchable: false, orderable: false },
                    { 'data': 'accreditation_details', searchable: false, orderable: false },
                    { 'data': 'contact_person', searchable: false, orderable: false },
                    { 'data': 'action', searchable: false, orderable: false, className: "text-center",
                        render: function(data, type, row, meta) {
                            if(isCanDelete('Employer Manager') && isCanWrite('Employer Manager')) {
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
                                        <a href="javascript:;" class="menu-link px-3 view-principal">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 edit-principal">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 remove-principal">Delete</a>
                                    </div>
                                </div>
                                `
                            } else if(!isCanDelete('Employer Manager') && isCanWrite('Employer Manager')) {
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
                                        <a href="javascript:;" class="menu-link px-3 view-principal">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 edit-principal">Edit</a>
                                    </div>
                                </div>
                                `
                            } else if(isCanDelete('Employer Manager') && !isCanWrite('Employer Manager')) {
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
                                        <a href="javascript:;" class="menu-link px-3 view-principal">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 remove-principal">Delete</a>
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
                                <div class="dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 view-principal">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 edit-principal">Edit</a>
                                    </div>
                                </div>
                                `
                            }
                        }
                    }
                ]
            });
        }

        const searchDatatable = _debounce( function () {
            let data = { search: page.search_table };
            $('#employer-table').DataTable().destroy();
            initDatatable(data);
        }, 500);

        const editPrincipal = async (id) => {
            router.push({
                name: 'client.employer.edit',
                params: {
                    id: id
                }
            });
        }

        const removePrincipal = (id) => {
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
                    await destroyPrincipal(page.idToDelete);
                    if(status.value == 200) {
                        $('#employer-table').DataTable().destroy();
                        initDatatable();
                    }
                }
            });
        }

        const viewPrincipal = (id) => {
            router.push({
                name: 'client.employer.show',
                params: {
                    id: id
                }
            })
        }

        const refresh = () => {
            $('#employer-table').DataTable().destroy();
            initDatatable();
        }

        const isCanWrite = (name) => {
            if(page.authuser.role_id != 1) {
                let permissions = page.authuser.role?.permissions;
                let array_permission = false;
                permissions.forEach(item => {
                    if(item.name == name) {
                        array_permission = item.can_write == 1;
                    }
                });

                return array_permission;
            }

            return true;
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
            initDatatable();

            $('tbody', '#employer-table').on( 'click', '.view-principal', function(){
                const cell =  $('#employer-table').DataTable().cell( $(this).closest("td") );
                viewPrincipal(cell.data());
            });

            $('tbody', '#employer-table').on( 'click', '.edit-principal', function(){
                const cell =  $('#employer-table').DataTable().cell( $(this).closest("td") );
                editPrincipal(cell.data());
            });

            $('tbody', '#employer-table').on( 'click', '.remove-principal', function(){
                const cell =  $('#employer-table').DataTable().cell( $(this).closest("td") );
                removePrincipal(cell.data());
            });
        });

        return {
            page,
            initialize,
            totalCount,
            initDatatable,
            searchDatatable,
            refresh,
            status,
            destroyPrincipal,
            viewPrincipal,
            isCanWrite,
            isCanDelete
        }
    }
}
</script>