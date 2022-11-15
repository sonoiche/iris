<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add Work Experience</h3>
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
                                            v-model="employment.position"
                                            label="Position"
                                            type="text"
                                            id="position"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="employment.company_name"
                                            label="Company Name"
                                            type="text"
                                            id="company_name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="employment.company_address"
                                            label="Company Address"
                                            type="text"
                                            id="company_address"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Country"
                                            :options="countries"
                                            :placeholder="`Select Country`"
                                            :defaultValue="{ id: employment.country_id, name: employment.country_name }"
                                            :is-clear="isClear"
                                            @select-value="setCountry"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="employment.department"
                                            label="Department"
                                            type="text"
                                            id="department"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">From</label>
                                        <date-picker v-model="state.from_date" inputClassName="form-control form-control-solid fc-calendar" monthPicker />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">To</label>
                                        <date-picker v-model="state.to_date" inputClassName="form-control form-control-solid fc-calendar" monthPicker />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Duties and Responsibilities</label>
                                        <base-editor :message="employment.duties" @save-content="saveContent" />
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
import employmentRepo from '@/repositories/applicants/employment';
import countryRepo from '@/repositories/employer/country';
import { ref, onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            from_date: '',
            to_date: '',
            addContinue: false
        });
        const { status, errors, employment, storeEmployment } = employmentRepo();
        const { countries, getSelectCountry } = countryRepo();

        const isSuccess = ref(false);
        const isContinue = ref(false);
        const isClear = ref(false);

        const setCountry = (value) => {
            employment.value.country_id = value.id;
        }

        const saveContent = (message) => {
            employment.value.duties = message;
        }

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
            let from_date = {
                month: state.from_date['month']+1,
                year: state.from_date['year']
            }

            let to_date = {
                month: state.to_date['month']+1,
                year: state.to_date['year']
            }

            let formData = new FormData();
            formData.append('position', employment.value.position ?? '');
            formData.append('company_name', employment.value.company_name ?? '');
            formData.append('company_address', employment.value.company_address ?? '');
            formData.append('department', employment.value.department ?? '');
            formData.append('country_id', employment.value.country_id ?? '');
            formData.append('from_date', JSON.stringify(from_date) ?? '');
            formData.append('to_date', JSON.stringify(to_date) ?? '');
            formData.append('duties', employment.value.duties ?? '');
            formData.append('applicant_id', route.params.id);
            await storeEmployment(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                employment.value = [];
                state.from_date = '';
                state.to_date = '';
                isClear.value = true;
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantEmployment');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantEmployment');
        }

        onMounted(() => {
            getSelectCountry();
        });

        return {
            state,
            status,
            errors,
            countries,
            getSelectCountry,
            employment,
            storeEmployment,
            isSuccess,
            isContinue,
            isClear,
            setCountry,
            saveContent,
            saveChanges,
            saveContinue,
            submitForm,
            backPage
        }
    },
}
</script>