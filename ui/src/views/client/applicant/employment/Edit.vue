<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Update Work Experience</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <loading v-if="state.isLoading" />
                        <div class="d-grid gap-2 col-8 mx-auto" v-else>
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
import employmentRepo from '@/repositories/applicants/employment';
import countryRepo from '@/repositories/employer/country';
import { ref, onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    props: {
        updateId: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            from_date: '',
            to_date: '',
            isLoading: true
        });
        const { status, errors, employment, updateEmployment, getEmployment } = employmentRepo();
        const { countries, getSelectCountry } = countryRepo();

        const isSuccess = ref(false);
        const isClear = ref(false);

        const setCountry = (value) => {
            employment.value.country_id = value.id;
            employment.value.country_name = value.name;
        }

        const saveContent = (message) => {
            employment.value.duties = message;
        }

        const saveChanges = () => {
            isSuccess.value = false;
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
            formData.append('id', employment.value.id ?? '');
            formData.append('_method', 'PUT');
            await updateEmployment(formData, props.updateId);
            isSuccess.value = true;
        }

        const backPage = () => {
            emit('add-data', 'ApplicantEmployment');
        }

        onMounted( async () => {
            await getEmployment(props.updateId);
            getSelectCountry();
            state.isLoading = false;
            state.from_date = employment.value.from_date;
            state.to_date = employment.value.to_date;
        });

        return {
            state,
            status,
            errors,
            countries,
            getSelectCountry,
            employment,
            getEmployment,
            updateEmployment,
            isSuccess,
            isClear,
            setCountry,
            saveContent,
            saveChanges,
            submitForm,
            backPage
        }
    },
}
</script>