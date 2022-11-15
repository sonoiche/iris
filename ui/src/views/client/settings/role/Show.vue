<template>
    <div>
        <Header :title="`Manage Roles`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 80%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10">
                                <div class="card card-flush">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2 class="mb-0">{{ role.name }}</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <loading v-if="page.isLeftSideLoading" />
                                        <div v-else>
                                            <div v-if="role.id == 1" class="d-flex flex-column text-gray-600">
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>All Admin Controls</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Settings</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Employers</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Applicants</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Processing</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Web Management</div>
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>View and Edit Reports</div>
                                            </div>
                                            <div v-else class="d-flex flex-column text-gray-600">
                                                <div v-for="(permission, index) in role.permissions" :key="permission">
                                                    <div v-if="index <= 8">
                                                        <div class="d-flex align-items-center py-2">
                                                        <span class="bullet bg-primary me-3"></span>{{ getPermissionText(permission) }}</div>
                                                    </div>
                                                </div>
                                                <div v-if="role.permissions?.length > 9">
                                                    <div class="d-flex align-items-center py-2">
                                                    <span class="bullet bg-primary me-3"></span>and {{ role.permissions.length - 9 }} more...</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer pt-0">
                                        <router-link :to="{ name: 'client.settings.role' }" class="btn btn-light btn-danger">Back</router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-lg-row-fluid ms-lg-9">
                                <div class="card card-flush mb-6 mb-xl-9">
                                    <div class="card-header pt-5">
                                        <div class="card-title">
                                            <h2 class="d-flex align-items-center">Users Assigned
                                            <span class="text-gray-600 fs-6 ms-1">({{ role.users?.length }})</span></h2>
                                        </div>
                                        <div class="card-toolbar">
                                            <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <input type="text" data-kt-roles-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Users" />
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center d-none" data-kt-view-roles-table-toolbar="selected">
                                                <div class="fw-bolder me-5">
                                                <span class="me-2" data-kt-view-roles-table-select="selected_count"></span>Selected</div>
                                                <button type="button" class="btn btn-danger" data-kt-view-roles-table-select="delete_selected">Delete Selected</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                                            <thead>
                                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                                    <th class="min-w-50px">#</th>
                                                    <th class="min-w-150px">User</th>
                                                    <th class="min-w-125px">Joined Date</th>
                                                    <th class="text-end min-w-100px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-600">
                                                <tr v-for="(user, index) in role.users" :key="user">
                                                    <td>{{ index+1 }}</td>
                                                    <td class="d-flex align-items-center">
                                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                            <a href="#">
                                                                <div class="symbol-label">
                                                                    <img :src="user.display_photo" :alt="user.fullname" class="w-100" />
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ user.fullname }}</a>
                                                            <span>{{ user.email }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ user.created_at_display }}</td>
                                                    <td class="text-end">
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
                                                    </td>
                                                </tr>
                                            </tbody>
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
</template>

<script>
import { onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';
import roleRepo from '@/repositories/settings/role';

export default {
    setup() {
        const route = useRoute();
        const page = reactive({
            isLeftSideLoading: true
        });
        const { role, getRole } = roleRepo();

        const getPermissionText = (item) => {
            if(item.read && item.write && item.delete) {
                return `View, Edit and Delete ${item.name}`;
            }

            if(item.read && item.write && !item.delete) {
                return `View and Edit ${item.name}`;
            }

            if(item.read && !item.write && !item.delete) {
                return `View ${item.name}`;
            }

            if(item.read && !item.write && item.delete) {
                return `View and Delete ${item.name}`;
            }
        }

        onMounted( async () => {
            await getRole(route.params.id);
            page.isLeftSideLoading = false;
        });

        return {
            page,
            role,
            getRole,
            getPermissionText
        }
    },
}
</script>