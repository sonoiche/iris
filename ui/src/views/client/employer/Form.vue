<template>
    <div>
        <loading v-if="isLoading" />
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework" v-else>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.name"
                        label="Company Name"
                        type="text"
                        id="name"
                        :errors="errors"
                        is-required
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.code"
                        label="Company Code"
                        type="text"
                        id="name"
                        :errors="errors"
                        :disabled="principal_id !== ''"
                        is-required
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="fv-row mb-0 fv-plugins-icon-container">
                        <label for="logo" class="form-label fs-6 fw-bolder mb-3">Company Logo</label>
                        <input 
                            type="file"
                            class="form-control form-control form-control-solid"
                            :class="{ 'is-invalid' : errors && errors['logo'] }" 
                            id="logo"
                            @change="onFileChange"
                            multiple enctype="multipart/form-data"
                        />
                        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['logo']">{{ errors['logo'][0] }}</label>
                        <div class="mt-3" v-if="principal.logo">
                            <small class="badge badge-primary fs-7 fw-bolder">
                                <a :href="principal.logo_link" target="_blank" class="text-white">{{ principal.logo_display }}</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Status"
                        :options="principal_status"
                        :placeholder="`Select Status`"
                        :errors="errors"
                        is-required
                        :id="`status`"
                        :defaultValue="{ id: principal.status, name: principal.status }"
                        @select-value="setStatus"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.address"
                        label="Address"
                        type="text"
                        id="address"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.website"
                        label="Website"
                        type="url"
                        id="website"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.landline"
                        label="Landline"
                        type="text"
                        id="landline"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.mobile_number"
                        label="Mobile Number"
                        type="text"
                        id="mobile_number"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Country"
                        :options="countries"
                        :placeholder="`Select Country`"
                        :defaultValue="{ id: principal.country_id, name: principal.country_name }"
                        @select-value="setCountry"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="principal.accreditation_number"
                        label="Accreditation Number"
                        type="text"
                        id="accreditation_number"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="principal.date_issue"
                        :defaultValue="principal.date_issue"
                        label="Date Issued"
                        id="date_issue"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="principal.date_expiry"
                        :defaultValue="principal.date_expiry"
                        label="Date Expiry"
                        id="date_expiry"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Industry"
                        :options="industries"
                        :placeholder="`Select Industry`"
                        :defaultValue="{ id: principal.industry_id, name: principal.industry_name }"
                        @select-value="setIndustry"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Assigned Users"
                        :options="users"
                        :placeholder="`Select Users`"
                        :multiple="true"
                        :defaultValue="principal.assigned_user_list"
                        @select-value="setUser"
                        @remove-value="removeUser"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <label class="form-label fs-6 fw-bolder mb-3">Notes</label>
                    <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="principal.remarks"></textarea>
                </div>
            </div>
            <div class="row mb-6 mt-3">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <div class="d-flex justify-content-between">
                        <router-link class="btn btn-outline-danger fw-bold" :to="{ name: 'client.employer' }">Cancel</router-link> &nbsp;&nbsp;
                        <base-button :success="isSuccess" @submit-form="saveChanges" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import principalRepo from '@/repositories/employer/principal';
import industryRepo from '@/repositories/employer/industry';
import countryRepo from '@/repositories/employer/country';
import userRepo from '@/repositories/settings/users';
import { onMounted, ref } from 'vue';

export default {
    props: {
        principal_id: {
            type: [String, Number],
            default: ''
        }
    },
    setup(props, {emit}) {
        const { status, errors, principal, getPrincipal, storePrincipal, updatePrincipal } = principalRepo();
        const { industries, getSelectIndustry } = industryRepo();
        const { countries, getSelectCountry } = countryRepo();
        const { users, getSelectUser } = userRepo();
        const principal_status = [
            { id: 'Active', name: 'Active' },
            { id: 'Inactive', name: 'Inactive' },
            { id: 'Prospect', name: 'Prospect' }
        ];
        const isSuccess = ref(false);
        const isLoading = ref(true);
        const assigned_users = ref([]);
        const logo = ref(null);

        const onFileChange = (e) => {
            const file = e.target.files[0];
            logo.value = file;
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', principal.value.name ?? '');
            formData.append('code', principal.value.code ?? '');
            formData.append('logo', logo.value ?? '');
            formData.append('status', principal.value.status ?? '');
            formData.append('address', principal.value.address ?? '');
            formData.append('website', principal.value.website ?? '');
            formData.append('landline', principal.value.landline ?? '');
            formData.append('mobile_number', principal.value.mobile_number ?? '');
            formData.append('country_id', principal.value.country_id ?? '');
            formData.append('accreditation_number', principal.value.accreditation_number ?? '');
            formData.append('date_issue', principal.value.date_issue ? new Date(principal.value.date_issue).toISOString() : '');
            formData.append('date_expiry', principal.value.date_expiry ? new Date(principal.value.date_expiry).toISOString() : '');
            formData.append('industry_id', principal.value.industry_id ?? '');
            formData.append('assigned_users', assigned_users.value ?? '');
            formData.append('remarks', principal.value.remarks ?? '');

            if(principal.value.id) {
                formData.append('_method', 'PUT');
                await updatePrincipal(formData, principal.value.id);
            } else {
                await storePrincipal(formData);
            }

            isSuccess.value = true;
            if(status.value == 200) {
                emit('submit-status', status.value);
            }
        }

        const setStatus = (value) => {
            principal.value.status = value;
            if(value) {
                errors.value.status = '';
            }
        }

        const setCountry = (value) => {
            principal.value.country_id = value;
        }

        const setIndustry = (value) => {
            principal.value.industry_id = value;
        }

        const setUser = (value) => {
            assigned_users.value.push(value);
        }

        const removeUser = (value) => {
            assigned_users.value.splice(assigned_users.value.indexOf(value), 1);
        }

        onMounted( async () => {
            getSelectIndustry();
            getSelectCountry();
            getSelectUser();
            if(props.principal_id) {
                await getPrincipal(props.principal_id);
                let current_assigned_users = principal.value.assigned_user_list;
                current_assigned_users.forEach(item => {
                    assigned_users.value.push(item.id);
                });
            }
            isLoading.value = false;
        });

        return {
            status,
            errors,
            principal,
            getPrincipal,
            storePrincipal,
            updatePrincipal,
            getSelectIndustry,
            principal_status,
            countries,
            industries,
            isSuccess,
            saveChanges,
            setStatus,
            setCountry,
            setIndustry,
            setUser,
            users,
            getSelectUser,
            onFileChange,
            isLoading,
            logo,
            removeUser
        }
    },
}
</script>

<style>
.fc-calendar {
    position: relative;
    padding-left: 40px !important;
}
</style>