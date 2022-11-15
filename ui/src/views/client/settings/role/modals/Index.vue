<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (role.id) ? `Update Role` : `Add a Role` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="d-flex flex-column scroll-y me-n7 pe-7" style="max-height: 650px;">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="role.name"
                                label="Role name"
                                type="text"
                                id="name"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="fv-row mt-5">
                        <label class="fs-5 fw-bolder form-label mb-2">Role Permissions</label>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <tbody class="text-gray-600 fw-bold">
                                    <tr>
                                        <td class="text-gray-800" style="width: 40%">Administrator Access 
                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Allows a full access to the system"></i></td>
                                        <td style="width: 60%">
                                            <label class="form-check form-check-custom form-check-solid me-9">
                                                <input class="form-check-input" type="checkbox" id="select-all" v-model="checkall" @change="setCheckboxAll" />
                                                <span class="form-check-label" for="select-all">Select all</span>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-if="!role.id">
                        <div class="fv-row mt-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <tbody class="text-gray-600 fw-bold" v-for="permission in permissions" :key="permission">
                                        <tr v-for="item in permission.items" :key="item">
                                            <td class="text-gray-800" style="width: 40%">{{ item.name }}</td>
                                            <td style="width: 60%">
                                                <div class="d-flex">
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20" v-if="item.read">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckbox" v-model="item.can_read" />
                                                        <span class="form-check-label">Read</span>
                                                    </label>
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20" v-if="item.write">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckbox" v-model="item.can_write" />
                                                        <span class="form-check-label">Write</span>
                                                    </label>
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid" v-if="item.delete">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckbox" v-model="item.can_delete" />
                                                        <span class="form-check-label">Delete</span>
                                                    </label>
                                                </div>
                                                <input type="hidden" v-model="item.read" />
                                                <input type="hidden" v-model="item.write" />
                                                <input type="hidden" v-model="item.delete" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="fv-row mt-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <tbody class="text-gray-600 fw-bold">
                                        <tr v-for="item in permissions" :key="item">
                                            <td class="text-gray-800" style="width: 40%">{{ item.name }}</td>
                                            <td style="width: 60%">
                                                <div class="d-flex">
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20" v-if="item.read">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckboxWithRole($event, item.id, 'can_read')" v-model="item.can_read" />
                                                        <span class="form-check-label">Read</span>
                                                    </label>
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20" v-if="item.write">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckboxWithRole($event, item.id, 'can_write')" v-model="item.can_write" />
                                                        <span class="form-check-label">Write</span>
                                                    </label>
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid" v-if="item.delete">
                                                        <input class="form-check-input" type="checkbox" @change="setCheckboxWithRole($event, item.id, 'can_delete')" v-model="item.can_delete" />
                                                        <span class="form-check-label">Delete</span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" @submit-form="saveChanges" />
            </template>
        </modal>
    </div>
</template>

<script>
import { onBeforeUpdate, reactive, ref } from 'vue';
import roleRepo from '@/repositories/settings/role';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        role: {
            type: Object,
            default: {}
        },
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    setup(props, {emit}) {
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true,
            totalCheckedbox: 0
        })
        const isSuccess = ref(false);
        const checkall = ref(false);
        const { errors, status, storeRole, updateRole, getPermissions, updateAllPermission, updatePermission, permissions } = roleRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', props.role.name ?? '');
            if(!props.role.id) {
                formData.append('permissions', JSON.stringify(permissions.value) ?? '');
                formData.append('agency_id', page.authuser.agency_id);
            }

            if(props.role.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.role.id);
                await updateRole(formData, props.role.id);
            } else {
                await storeRole(formData);
            }
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
            }
        }

        const cancel = () => {
            errors.value = [];
            emit('close-modal');
        }

        const setCheckbox = (event) => {
            page.totalCheckedbox = 0;
            let checkboxes = permissions.value;
            let total = 0;
            checkboxes.forEach(item => {
                page.totalCheckedbox == 0;
                item.items.forEach(checkbox => {
                    if(checkbox.can_read == true) {
                        page.totalCheckedbox++;
                    }

                    if(checkbox.can_write == true) {
                        page.totalCheckedbox++;
                    }

                    if(checkbox.can_delete == true) {
                        page.totalCheckedbox++;
                    }
                });
            });
            
            if(event.target.checked == true && page.totalCheckedbox > 90) {
                total = page.totalCheckedbox - 15;
            } else {
                total = page.totalCheckedbox - 15;
            }

            if(total == 90) {
                checkall.value = true;
            } else {
                checkall.value = false;
            }
        }

        const setCheckboxWithRole = (event, id, type) => {
            page.totalCheckedbox = 0;
            let checkboxes = permissions.value;
            let total = 0;
            checkboxes.forEach(item => {
                if(item.can_read == true) {
                    page.totalCheckedbox++;
                }

                if(item.can_write == true) {
                    page.totalCheckedbox++;
                }

                if(item.can_delete == true) {
                    page.totalCheckedbox++;
                }
            });

            if(event.target.checked == true && page.totalCheckedbox > 90) {
                total = page.totalCheckedbox - 15;
            } else {
                total = page.totalCheckedbox - 15;
            }

            if(total == 90) {
                checkall.value = true;
            } else {
                checkall.value = false;
            }

            updatePermission({ id: id, event: event.target.checked, type: type});
        }

        const setCheckboxAll = (event) => {
            let checkboxes = permissions.value;
            if(event.target.checked == true && !props.role.id) {
                checkboxes.forEach(item => {
                    item.items.forEach(checkbox => {
                        checkbox.can_read = true;
                        checkbox.can_write = true;
                        checkbox.can_delete = true;
                    });
                });
            } else if(event.target.checked == true && props.role.id) {
                checkboxes.forEach(item => {
                    item.can_read = true;
                    item.can_write = true;
                    item.can_delete = true;
                });
                updateAllPermission({ role_id: props.role.id, type: true});
            } else if(event.target.checked == false && props.role.id) {
                checkboxes.forEach(item => {
                    item.can_read = false;
                    item.can_write = false;
                    item.can_delete = false;
                });
                updateAllPermission({ role_id: props.role.id, type: false});
            } else {
                checkboxes.forEach(item => {
                    item.items.forEach(checkbox => {
                        checkbox.can_read = false;
                        checkbox.can_write = false;
                        checkbox.can_delete = false;
                    });
                });
            }
        }

        onBeforeUpdate(() => {
            checkall.value = false;
            getPermissions(props.role.id);
            if(props.role.id) {
                let checkboxes = permissions.value;
                let total = 0;
                page.totalCheckedbox = 0;
                checkboxes.forEach(item => {
                    if(item.can_read == true) {
                        page.totalCheckedbox++;
                    }

                    if(item.can_write == true) {
                        page.totalCheckedbox++;
                    }

                    if(item.can_delete == true) {
                        page.totalCheckedbox++;
                    }
                });
                
                total = page.totalCheckedbox - 15;

                if(total == 90) {
                    checkall.value = true;
                } else {
                    checkall.value = false;
                }
            }
        });

        return {
            page,
            isSuccess,
            checkall,
            saveChanges,
            cancel,
            setCheckbox,
            setCheckboxAll,
            setCheckboxWithRole,
            errors,
            status,
            storeRole,
            updateRole,
            getPermissions,
            updateAllPermission,
            updatePermission,
            permissions
        }
    },
}
</script>