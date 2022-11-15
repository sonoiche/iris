<template>
    <div>
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0 d-flex justify-content-between">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Active Contacts</h3>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary btn-sm" @click="addContact">Add Contact</button>
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
                        <input type="text" class="form-control form-control-solid w-250px ps-14" v-model="page.search_table" @keyup="searchDatatable" placeholder="Search Active Contacts" />
                    </div>
                </div>
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="contact-table">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">#</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Position</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Contact Details</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold"></tbody>
                </table>
            </div>
        </div>
        <ModalContact :is-active="modalActive" :contact_id="contact_id" :principal_id="principal_id" :isLoading="page.isLoading" @close-modal="closeModalContact" @refresh-table="refresh" />
    </div>
</template>

<script>
import { ref, reactive, onMounted, inject } from 'vue';
import _debounce from 'lodash/debounce';
import principalContactRepo from '@/repositories/employer/contact';
import ModalContact from '@/views/client/employer/modals/contact/Create.vue';
import $ from 'jquery';
require('/public/assets/js/datatables.js');
require('/public/assets/plugins/custom/datatables/datatables.bundle.css');

export default {
    props: {
        principal_id: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props) {
        const swal = inject('$swal');
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            search_table: '',
            isLoading: true
        });
        const { status, destroyPrincipalContact } = principalContactRepo();
        const modalActive = ref(false);
        const contact_id = ref('');
        const initialize = ref(false);
        const totalCount = ref(0);

        const addContact = () => {
            modalActive.value = true;
            page.isLoading = false;
        }

        const closeModalContact = () => {
            modalActive.value = false;
        }

        const initDatatable = (result = {}) => {
            window.$ = window.jQuery = require('jquery');
            $.noConflict();
            initialize.value = true;
            $('#contact-table').DataTable({
                'processing': true,
                'serverSide': true,
                ajax: {
                    url: `${process.env.VUE_APP_API_ENDPOINT}/client/principal-contacts/datatable`,
                    type: 'POST',
                    data: {
                        search: result.search ?? '',
                        principal_id: props.principal_id
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
                    { 'data': 'position', searchable: false, orderable: false },
                    { 'data': 'email', searchable: true, orderable: true },
                    { 'data': 'mobile_number', searchable: true, orderable: true },
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
                                    <a href="javascript:;" class="menu-link px-3 edit-contact">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" class="menu-link px-3 remove-contact">Delete</a>
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
            $('#contact-table').DataTable().destroy();
            initDatatable(data);
        }, 500);

        const editContact = async (id) => {
            modalActive.value = true;
            contact_id.value = id;
            page.isLoading = false;
        }

        const removeContact = (id) => {
            swal({
                title: 'Are you sure?',
                text: "You want to delete this contact?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    await destroyPrincipalContact(id);
                    if(status.value == 200) {
                        $('#contact-table').DataTable().destroy();
                        initDatatable();
                    }
                }
            });
        }

        const refresh = () => {
            $('#contact-table').DataTable().destroy();
            initDatatable();
        }

        onMounted(() => {
            initDatatable();

            $('tbody', '#contact-table').on( 'click', '.edit-contact', function(){
                const cell =  $('#contact-table').DataTable().cell( $(this).closest("td") );
                editContact(cell.data());
            });

            $('tbody', '#contact-table').on( 'click', '.remove-contact', function(){
                const cell =  $('#contact-table').DataTable().cell( $(this).closest("td") );
                removeContact(cell.data());
            });
        });

        return {
            page,
            modalActive,
            contact_id,
            initialize,
            totalCount,
            initDatatable,
            addContact,
            closeModalContact,
            searchDatatable,
            editContact,
            removeContact,
            status,
            destroyPrincipalContact,
            refresh
        }
    },
    components: {
        ModalContact
    }
}
</script>