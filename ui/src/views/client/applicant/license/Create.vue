<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add License / Certification</h3>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-success btn-sm" @click="backPage">Back</button>
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
                                        <BaseInput
                                            v-model="license.title"
                                            label="License/Certification Name"
                                            type="text"
                                            id="title"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="license.license_number"
                                            label="License/Certification Number"
                                            type="text"
                                            id="license_number"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date First Issued</label>
                                        <date-picker v-model="state.date_issue" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
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
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Expiry</label>
                                        <date-picker v-model="state.date_expiry" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6 mt-3">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="d-flex justify-content-end">
                                            <base-button :success="isSuccess" :btn-text="`Save & Add Another`" @submit-form="saveChanges" /> &nbsp;&nbsp;
                                            <base-button :success="isContinue" :btn-text="`Save & Continue`" @submit-form="saveContinue" />
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
import licenseRepo from '@/repositories/applicants/license';
import { ref, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            date_issue: '',
            date_taken: '',
            date_expiry: '',
            addContinue: false
        });
        const { status, errors, license, storeLicense } = licenseRepo();

        const isSuccess = ref(false);
        const isContinue = ref(false);

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const saveContinue = () => {
            isContinue.value = false;
            state.addContinue = true;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('title', license.value.title ?? '');
            formData.append('license_number', license.value.license_number ?? '');
            formData.append('date_issue', (state.date_issue) ? new Date(state.date_issue).toISOString() : '');
            formData.append('date_taken', (state.date_taken) ? new Date(state.date_taken).toISOString() : '');
            formData.append('date_expiry', (state.date_expiry) ? new Date(state.date_expiry).toISOString() : '');
            formData.append('applicant_id', route.params.id);
            await storeLicense(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                license.value = [];
                state.date_issue = '';
                state.date_taken = '';
                state.date_expiry = '';
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantLicense');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantLicense');
        }

        return {
            state,
            status,
            errors,
            license,
            storeLicense,
            isSuccess,
            isContinue,
            saveChanges,
            saveContinue,
            submitForm,
            backPage
        }
    },
}
</script>