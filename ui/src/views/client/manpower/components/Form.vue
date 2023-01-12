<template>
    <div>
        <loading v-if="isLoading" />
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework" v-else>
            <div class="row mb-6">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Principal"
                        :options="principals"
                        :placeholder="`Select Principal`"
                        id="principal_id"
                        :defaultValue="{ id: joborder.principal_id, name: joborder.principal_name }"
                        :errors="errors"
                        is-required
                        @select-value="setPrincipal"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="joborder.date_receive"
                        :defaultValue="joborder.date_receive"
                        label="Date Receive"
                        id="date_receive"
                        :errors="errors"
                        is-required
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="joborder.date_needed"
                        :defaultValue="joborder.date_needed"
                        label="Date Needed"
                        id="date_needed"
                        :errors="errors"
                        is-required
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="joborder.date_expiry"
                        :defaultValue="joborder.date_expiry"
                        label="Date Expiry"
                        id="date_expiry"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Status"
                        :options="joborder_status"
                        :placeholder="`Select Status`"
                        :defaultValue="{ id: joborder.status, name: joborder.status }"
                        :errors="errors"
                        is-required
                        id="status"
                        @select-value="setStatus"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <label for="job_order_type" class="form-label fs-6 fw-bolder mb-3">Job Order Type</label>
                    <div>
                        <div class="form-check form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" type="radio" v-model="joborder.job_type" value="Local" id="local"/>
                            <label class="form-check-label" for="local">
                                Local
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid mb-3">
                            <input class="form-check-input" type="radio" v-model="joborder.job_type" value="International" id="international"/>
                            <label class="form-check-label" for="international">
                                International
                            </label>
                        </div>
                        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors.job_type">{{ errors.job_type[0] }}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-6 mt-3">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <div class="d-flex justify-content-between">
                        <router-link v-if="!joborder.id" class="btn btn-outline-danger fw-bold" :to="{ name: 'client.joborder' }">Cancel</router-link> &nbsp;&nbsp;
                        <base-button :success="isSuccess" @submit-form="saveChanges" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import joborderRepo from '@/repositories/employer/joborder';
import principalRepo from '@/repositories/employer/principal';
import { onMounted, ref } from '@vue/runtime-core';

export default {
    props: {
        job_order_id: {
            type: [String, Number],
            default: ''
        }
    },
    setup(props, {emit}) {
        const authuser = JSON.parse(localStorage.getItem('authuser'));
        const joborder_status = [
            { id: 'Active', name: 'Active' },
            { id: 'Inactive', name: 'Inactive' }
        ];
        const { errors, status, joborder, getJobOrder, storeJobOrder, updateJobOrder } = joborderRepo();
        const { principals, getSelectPrincipal } = principalRepo();

        const isLoading = ref(true);
        const isSuccess = ref(false);

        const setPrincipal = (value) => {
            joborder.value.principal_id = value.id;
        }

        const setStatus = (value) => {
            joborder.value.status = value.id;
            if(value) {
                errors.value.status = '';
            }
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('principal_id', joborder.value.principal_id ?? '');
            formData.append('date_receive', joborder.value.date_receive ? new Date(joborder.value.date_receive).toISOString() : '');
            formData.append('date_needed', joborder.value.date_needed ? new Date(joborder.value.date_needed).toISOString() : '' ?? '');
            formData.append('date_expiry', joborder.value.date_expiry ? new Date(joborder.value.date_expiry).toISOString() : '' ?? '');
            formData.append('status', joborder.value.status ?? '');
            formData.append('job_type', joborder.value.job_type ?? '');
            formData.append('user_id', authuser.id);

            if(joborder.value.id) {
                formData.append('_method', 'PUT');
                await updateJobOrder(formData, joborder.value.id);
            } else {
                await storeJobOrder(formData);
            }

            isSuccess.value = true;
            if(status.value == 200) {
                emit('submit-status', status.value);
            }
        }

        onMounted( async () => {
            getSelectPrincipal();
            if(props.job_order_id) {
                await getJobOrder(props.job_order_id);
            }
            isLoading.value = false;
        });

        return {
            joborder_status,
            joborder,
            getJobOrder,
            principals,
            getSelectPrincipal,
            isLoading,
            isSuccess,
            setPrincipal,
            setStatus,
            saveChanges,
            errors,
            status,
            storeJobOrder,
            updateJobOrder
        }
    },
}
</script>