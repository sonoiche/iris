<template>
    <div>
        <Header :title="`Manage Roles`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <loading v-if="page.isContentLoading" />
                        <div v-else class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
                            <div class="col-md-4" v-for="role in roles" :key="role">
                                <div class="card card-flush h-md-100">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>{{ role.name }}</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-1">
                                        <div class="fw-bolder text-gray-600 mb-5">Total users with this role: {{ role.users?.length }}</div>
                                        <div class="d-flex flex-column text-gray-600" v-if="role.id == 1">
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
                                        <div class="d-flex flex-column text-gray-600" v-else>
                                            <div v-for="(permission, index) in role.permissions" :key="permission">
                                                <div v-if="index <= 5">
                                                    <div class="d-flex align-items-center py-2">
                                                    <span class="bullet bg-primary me-3"></span>{{ getPermissionText(permission) }}</div>
                                                </div>
                                            </div>
                                            <div v-if="role.permissions.length > 6">
                                                <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>and {{ role.permissions.length - 6 }} more...</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer flex-wrap pt-0">
                                        <router-link :to="{ name: 'client.settings.role-show', params: { id: role.id } }" class="btn btn-light btn-active-primary my-1 me-2">View Role</router-link>
                                        <button v-if="role.id!=1" type="button" class="btn btn-light btn-active-light-primary my-1" @click="editRole(role.id)">Edit Role</button>
                                    </div>
                                </div>
                            </div>
                            <div class="ol-md-4">
                                <div class="card h-md-100" v-if="isCanWrite('Role Manager')">
                                    <div class="card-body d-flex flex-center">
                                        <button type="button" class="btn btn-clear d-flex flex-column flex-center" @click="addRole">
                                            <img src="/assets/media/illustrations/sketchy-1/4.png" alt="" class="mw-100 mh-150px mb-7" />
                                            <div class="fw-bolder fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ModalRole :is-active="modalActive" :role="role" :isLoading="page.isLoading" @close-modal="closeModalRole" />
    </div>
</template>

<script>
import roleRepo from '@/repositories/settings/role';
import { onMounted, ref, reactive } from 'vue';
import ModalRole from '@/views/client/settings/role/modals/Index.vue';

export default {
    setup() {
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            search_table: '',
            isLoading: true,
            isContentLoading: true,
            idToDelete: ''
        });
        const { roles, role, getRoles, getRole, destroyRole } = roleRepo();
        const modalActive = ref(false);

        const addRole = () => {
            modalActive.value = true;
            setTimeout(() => {
                page.isLoading = false;
            }, 800);
        }

        const editRole = async (id) => {
            await getRole(id);
            modalActive.value = true;
            setTimeout(() => {
                page.isLoading = false;
            }, 800);
        }

        const closeModalRole = () => {
            modalActive.value = false;
            page.isLoading = true;
        }

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

        onMounted(() => {
            getRoles(page.authuser.agency_id);
            setTimeout(() => {
                page.isContentLoading = false;
            }, 800);
        });

        return {
            page,
            roles,
            role,
            getRoles,
            getRole,
            destroyRole,
            modalActive,
            addRole,
            editRole,
            closeModalRole,
            getPermissionText,
            isCanWrite
        }
    },
    components: {
        ModalRole
    }
}
</script>