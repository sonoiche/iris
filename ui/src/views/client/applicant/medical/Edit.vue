<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Update Applicant medical</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <div class="d-grid gap-2 col-8 mx-auto">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Clinic"
                                            :options="clinicOptions"
                                            :placeholder="`Select Clinic`"
                                            :defaultValue="{ id: medical.clinic_id, name: medical.clinic_name }"
                                            id="clinic_id"
                                            :is-clear="isClear"
                                            @select-value="setClinic"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Referred</label>
                                        <date-picker v-model="state.date_referred" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Taken</label>
                                        <date-picker v-model="state.date_taken" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Result</label>
                                        <date-picker v-model="state.date_result" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Expiry</label>
                                        <date-picker v-model="state.date_expiry" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Status"
                                            :options="medicalStatus"
                                            :placeholder="`Select Status`"
                                            :id="`status`"
                                            :defaultValue="{ id: medical.status, name: medical.status }"
                                            @select-value="setMedicalStatus"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                        <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="medical.remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-6 mt-3">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-outline-danger btn-sm" @click="backPage">Cancel</button> &nbsp;&nbsp;
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
import clinicRepo from '@/repositories/settings/clinic';
import medicalRepo from '@/repositories/applicants/medical';
import { ref, reactive, computed, onMounted } from 'vue';

export default {
    props: {
        updateId: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            date_referred: '',
            date_taken: '',
            date_result: '',
            date_expiry: '',
            addContinue: false
        });
        const { clinics, getClinics } = clinicRepo();
        const { status, errors, medical, updateMedical, getMedical } = medicalRepo();

        const medicalStatus = [
            { id: 'Fit', name: 'Fit' },
            { id: 'Unfit', name: 'Unfit' },
            { id: 'Pending', name: 'Pending' },
        ];

        const isSuccess = ref(false);
        const isClear = ref(false);

        const clinicOptions = computed(() => {
            const arr_clinics = [];
            clinics.value.forEach(item => {
                arr_clinics.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_clinics;
        });

        const setClinic = (value) => {
            medical.value.clinic_id = value.id;
            medical.value.clinic_name = value.name;
        }

        const setMedicalStatus = (value) => {
            medical.value.status = value.id;
        }

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('clinic_id', medical.value.clinic_id ?? '');
            formData.append('status', medical.value.status ?? '');
            formData.append('remarks', medical.value.remarks ?? '');
            formData.append('date_referred', (state.date_referred) ? new Date(state.date_referred).toISOString() : '');
            formData.append('date_expiry', (state.date_expiry) ? new Date(state.date_expiry).toISOString() : '');
            formData.append('date_taken', (state.date_taken) ? new Date(state.date_taken).toISOString() : '');
            formData.append('date_result', (state.date_result) ? new Date(state.date_result).toISOString() : '');
            formData.append('id', medical.value.id ?? '');
            formData.append('user_id', state.authuser.id);
            formData.append('_method', 'PUT');
            await updateMedical(formData, props.updateId);
            isSuccess.value = true;
        }

        const backPage = () => {
            emit('add-data', 'ApplicantMedical');
        }

        onMounted( async () => {
            await getMedical(props.updateId);
            state.date_referred = medical.value.date_referred;
            state.date_expiry = medical.value.date_expiry;
            state.date_taken = medical.value.date_taken;
            state.date_result = medical.value.date_result;
        });

        return {
            state,
            status,
            errors,
            medical,
            updateMedical,
            getMedical,
            isSuccess,
            saveChanges,
            submitForm,
            backPage,
            medicalStatus,
            clinicOptions,
            setMedicalStatus,
            setClinic,
            clinics,
            getClinics,
            isClear
        }
    },
}
</script>