<template>
    <div>
        <Header :title="`User List`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <div class="card mb-5 mb-xl-12">
                            <div class="card-header border-0 d-flex justify-content-between">
                                <div class="card-title">
                                    <h3 class="fw-bolder m-0">User List</h3>
                                </div>
                                <div class="d-flex align-items-center" v-if="isCanWrite('User Manager')">
                                    <button class="btn btn-primary btn-sm" @click="addUser">Add New User</button>
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
                                        <input type="text" class="form-control form-control-solid w-250px ps-14" v-model="page.search_table" @keyup="searchDatatable" placeholder="Search User" />
                                    </div>
                                </div>
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-users">
                                    <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px">User</th>
                                            <th class="min-w-125px">Role</th>
                                            <th class="min-w-125px">Last login</th>
                                            <th class="min-w-125px">Two-step</th>
                                            <th class="min-w-125px">Joined Date</th>
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
        <ModalUser :is-active="modalActive" :user="user" :isLoading="page.isLoading" @close-modal="closeModalUser" @refresh-table="refresh" />
    </div>
</template>

<script>
import { ref, onMounted, reactive, inject } from '@vue/runtime-core';
import $ from 'jquery';
import _debounce from 'lodash/debounce';
import userRepo from '@/repositories/settings/users';
import ModalUser from '@/views/client/settings/user/modals/Index.vue';
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

        const { user, getUser, destroyUser, status } = userRepo();

        const initialize = ref(false);
        const modalActive = ref(false);
        const totalCount = ref(0);

        const addUser = () => {
            modalActive.value = true;
            page.isLoading = false;
            user.value = {};
        }

        const closeModalUser = () => {
            modalActive.value = false;
            page.isLoading = true;
        }

        const refresh = () => {
            $('#table-users').DataTable().destroy();
            initDatatable();
        }

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#table-users').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/users/datatable`,
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
                    { 'data': 'name', searchable: true, orderable: true,
                        render: function(data, type, row, meta) {
                            return `
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <a href="#!">
                                            <div class="symbol-label">
                                                <img src="${data['photo']}" class="w-100" />
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#!" class="text-gray-800 text-hover-primary mb-1">${data['fullname']}</a>
                                        <span>${data['email']}</span>
                                    </div>
                                </div>
                            `
                        }
                    },
                    { 'data': 'role', searchable: false, orderable: true },
                    { 'data': 'last_login', searchable: false, orderable: true,
                        render: function(data, type, row, meta) {
                            return `<div class="badge badge-light fw-bolder">Yesterday</div>`
                        }
                    },
                    { 'data': 'two_step', searchable: false, orderable: false, className: "text-center",
                        render: function(data, type, row, meta) {
                            return `<div class="badge badge-light-success fw-bolder">Enabled</div>`
                        }
                    },
                    { 'data': 'created_at', searchable: false, orderable: true },
                    { 'data': 'action', searchable: false, orderable: false, className: "text-center",
                        render: function(data, type, row, meta) {
                            if(data != page.authuser.id && isCanDelete('User Manager')) {
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
                                        <a href="javascript:;" class="menu-link px-3 edit-user">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" class="menu-link px-3 remove-user">Delete</a>
                                    </div>
                                </div>
                                `
                            } else {
                                return ``;
                            }
                        }
                    }
                ]
            });
        }

        const searchDatatable = _debounce( function () {
            let data = { search: page.search_table };
            $('#table-users').DataTable().destroy();
            initDatatable(data);
        }, 500);

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

        const editUser = async (id) => {
            initialize.value = false;
            $('#applicants-table').DataTable().destroy();
            await getUser(id);
            modalActive.value = true;
            page.isLoading = false;
        }

        const removeUser = (id) => {
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
                    initialize.value = false;
                    $('#source-table').DataTable().destroy();
                    await destroyUser(page.idToDelete);
                    if(status.value == 200) {
                        initDatatable();
                    }
                }
            });
        }

        onMounted(() => {
            initDatatable();

            $('tbody', '#table-users').on( 'click', '.edit-user', function(){
                const cell =  $('#table-users').DataTable().cell( $(this).closest("td") );
                editUser(cell.data());
            });

            $('tbody', '#table-users').on( 'click', '.remove-user', function(){
                const cell =  $('#table-users').DataTable().cell( $(this).closest("td") );
                removeUser(cell.data());
            });
        });

        return {
            page,
            initialize,
            modalActive,
            totalCount,
            addUser,
            initDatatable,
            searchDatatable,
            closeModalUser,
            refresh,
            user,
            getUser,
            destroyUser,
            status,
            isCanWrite,
            isCanDelete
        }
    },
    components: {
        ModalUser
    }
}
</script>