<template>
    <div>
        <modal :active="isActive" :size="`xl`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (user.id) ? `Update User Info` : `Add New User` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-4 col-md-12">
                            <BaseInput 
                                v-model="user.fname"
                                label="First Name"
                                type="text"
                                id="fname"
                                :errors="errors"
                                is-required
                            />
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <BaseInput 
                                v-model="user.mname"
                                label="Middle Name (Optional)"
                                type="text"
                                id="mname"
                            />
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <BaseInput 
                                v-model="user.lname"
                                label="Last Name"
                                type="text"
                                id="lname"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-12">
                            <BaseInput 
                                v-model="user.email"
                                label="Email Address"
                                type="email"
                                id="email"
                                :errors="errors"
                                is-required
                            />
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <BaseSelect 
                                v-model="user.role_id"
                                label="Role"
                                :options="roles"
                                id="role_id"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container mb-3">
                                <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                <textarea rows="4" style="width: 100%" class="form-control form-control-lg form-control-solid" v-model="user.remarks"></textarea>
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
import userRepo from '@/repositories/settings/users';
import roleRepo from '@/repositories/settings/role';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        user: {
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
            isLoading: true
        })
        const isSuccess = ref(false);
        const { errors, status, storeUser, updateUser } = userRepo();
        const { roles, getRoles } = roleRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('fname', props.user.fname ?? '');
            formData.append('mname', props.user.mname ?? '');
            formData.append('lname', props.user.lname ?? '');
            formData.append('email', props.user.email ?? '');
            formData.append('role_id', props.user.role_id ?? '');
            formData.append('remarks', props.user.remarks ?? '');
            formData.append('agency_id', page.authuser.agency_id);
            formData.append('user_id', page.authuser.id);

            if(props.user.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.user.id);
                await updateUser(formData, props.user.id);
            } else {
                await storeUser(formData);
            }
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const cancel = () => {
            errors.value = [];
            emit('close-modal');
        }

        onBeforeUpdate(() => {
            getRoles(page.authuser.agency_id);
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            storeUser,
            updateUser,
            roles,
            getRoles
        }
    },
}
</script>