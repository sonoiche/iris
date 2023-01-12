<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Applicant Processing</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <div class="d-grid gap-2 col-8 mx-auto">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="processing.employer"
                                            label="Actual Employer"
                                            type="text"
                                            id="employer"
                                        />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Principal"
                                            :options="principals"
                                            :placeholder="`Select Principal`"
                                            :defaultValue="{ id: processing.principal_id, name: processing.principal_name }"
                                            id="principal_id"
                                            :is-clear="isClear"
                                            @select-value="setPrincipal"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="processing.salary"
                                            label="Agreed Salary"
                                            type="text"
                                            id="salary"
                                        />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <label for="direct_hire" class="form-label fs-6 fw-bolder mb-3">Direct Hire</label>
                                        <div class="d-flex align-items-center" style="height: 40px;">
                                            <div class="form-check form-check-custom form-check-solid mr-15">
                                                <input class="form-check-input" type="radio" v-model="processing.direct_hire" value="yes" id="yes"/>
                                                <label class="form-check-label" for="yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" v-model="processing.direct_hire" value="no" id="no"/>
                                                <label class="form-check-label" for="no">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="processing.worksite"
                                            label="Worksite"
                                            type="text"
                                            id="worksite"
                                        />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Country"
                                            :options="countries"
                                            :placeholder="`Select Country`"
                                            :defaultValue="{ id: processing.country_id, name: processing.country_name }"
                                            @select-value="setCountry"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Job Order Number"
                                            :options="jobOrderOptions"
                                            :placeholder="`Select Job Order`"
                                            :defaultValue="{ id: processing.job_order_number, name: processing.job_order_number }"
                                            id="job_order_number"
                                            :is-clear="isClear"
                                            @select-value="setJobOrder"
                                        />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                            <BaseSelect
                                                label="Job Order Position"
                                                :options="positionOptions"
                                                :placeholder="`Select Job Order Position`"
                                                :defaultValue="{ id: processing.position_id, name: processing.position_title }"
                                                id="position_id"
                                                :is-clear="isClear"
                                                @select-value="setJobOrderPosition"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                            <label class="form-label fs-6 fw-bolder mb-3">Endorsement Date</label>
                                            <date-picker v-model="state.date_endorse" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                            <label class="form-label fs-6 fw-bolder mb-3">Deployed Date</label>
                                            <date-picker v-model="state.deployed_date" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 mt-3">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="d-flex justify-content-end">
                                            <base-button :success="isSuccess" @submit-form="saveChanges" />
                                        </div>
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
import countryRepo from '@/repositories/employer/country';
import principalRepo from '@/repositories/employer/principal';
import joborderRepo from '@/repositories/employer/joborder';
import positionRepo from '@/repositories/employer/position';
import processRepo from '@/repositories/applicants/process';
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            date_endorse: '',
            deployed_date: '',
            addContinue: false,
            authuser: JSON.parse(localStorage.getItem('authuser')),
        });
        const { principals, getSelectPrincipal } = principalRepo();
        const { countries, getSelectCountry } = countryRepo();
        const { joborders, getJobOrdersByPrincipal } = joborderRepo();
        const { positions, getPositionByJobOrder } = positionRepo();
        const { status, errors, processing, storeProcess, getProcessing } = processRepo();

        const isSuccess = ref(false);
        const isClear = ref(false);

        const jobOrderOptions = computed(() => {
            const arr_joborders = [];
            joborders.value.forEach(item => {
                arr_joborders.push({
                    id: item.id,
                    name: item.job_order_number
                });
            });

            return arr_joborders;
        });

        const positionOptions = computed(() => {
            const arr_position = [];
            positions.value.forEach(item => {
                arr_position.push({
                    id: item.id,
                    name: item.position_title
                });
            });

            return arr_position;
        });

        const setPrincipal = async (value) => {
            processing.value.principal_id = value.id;
            processing.value.principal_name = value.name;
            await getJobOrdersByPrincipal(value.id);
        }

        const setCountry = (value) => {
            processing.value.country_id = value.id;
            processing.value.country_name = value.name;
        }

        const setJobOrder = async (value) => {
            processing.value.job_order_number = value.name;
            await getPositionByJobOrder(value.id);
        }

        const setJobOrderPosition = (value) => {
            processing.value.position_id = value.id;
            processing.value.position_title = value.name;
        }

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('employer', processing.value.employer ?? '');
            formData.append('principal_id', processing.value.principal_id ?? '');
            formData.append('salary', processing.value.salary ?? '');
            formData.append('direct_hire', processing.value.direct_hire ?? '');
            formData.append('worksite', processing.value.worksite ?? '');
            formData.append('country_id', processing.value.country_id ?? '');
            formData.append('job_order_number', processing.value.job_order_number ?? '');
            formData.append('position_id', processing.value.position_id ?? '');
            formData.append('date_endorse', (state.date_endorse) ? new Date(state.date_endorse).toISOString() : '');
            formData.append('deployed_date', (state.deployed_date) ? new Date(state.deployed_date).toISOString() : '');
            formData.append('applicant_id', route.params.id);
            formData.append('user_id', state.authuser.id);
            await storeProcess(formData);
            isSuccess.value = true;

            if(status.value == 200) {
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantProcessing');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantProcessing');
        }

        onMounted( async () => {
            getSelectCountry();
            getSelectPrincipal();
            await getProcessing(route.params.id);
            await getJobOrdersByPrincipal(processing.value.principal_id);
            state.date_endorse = processing.value.date_endorse;
        });

        return {
            state,
            status,
            errors,
            processing,
            storeProcess,
            isSuccess,
            saveChanges,
            submitForm,
            backPage,
            countries,
            getSelectCountry,
            setPrincipal,
            setCountry,
            setJobOrder,
            setJobOrderPosition,
            isClear,
            principals,
            getSelectPrincipal,
            getProcessing,
            jobOrderOptions,
            positionOptions,
            joborders,
            positions,
            getPositionByJobOrder,
            getJobOrdersByPrincipal
        }
    },
}
</script>

<style scoped>
.mr-15 {
    margin-right: 15px !important;
}
</style>