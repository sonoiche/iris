<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Update Educational Background</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <loading v-if="state.isLoading" />
                        <div class="d-grid gap-2 col-8 mx-auto" v-else>
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Educational Level"
                                            :options="education_levels"
                                            :placeholder="`Select Educational Level`"
                                            :defaultValue="{ id: education.education_level, name: education.education_level_name }"
                                            id="education_level"
                                            :errors="errors"
                                            is-required
                                            @select-value="setEducation"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Field of Study"
                                            :options="studies"
                                            :placeholder="`Select Field of Study`"
                                            :defaultValue="{ id: education.field_study, name: education.field_study_name }"
                                            @select-value="setStudy"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="education.course"
                                            label="Course"
                                            type="text"
                                            id="course"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="education.school"
                                            label="University / School"
                                            type="text"
                                            id="school"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="education.location"
                                            label="Location"
                                            type="text"
                                            id="location"
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
                                        <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                        <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="education.remarks"></textarea>
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
import educationRepo from '@/repositories/applicants/education';
import { ref, onMounted, reactive } from 'vue';

export default {
    props: {
        updateId: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            from_date: '',
            to_date: '',
            isLoading: true,
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const { status, errors, studies, education_levels, education, updateEducation, getEducationLevels, getFieldStudy, getEducation } = educationRepo();

        const isSuccess = ref(false);

        const setEducation = (value) => {
            education.value.education_level = value.id;
            education.value.education_level_name = value.name;
        }

        const setStudy = (value) => {
            education.value.field_study = value.id;
            education.value.field_study_name = value.name;
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let from_date = {
                month: state.from_date['month']+1,
                year: state.from_date['year']
            }

            let to_date = {
                month: state.to_date['month']+1,
                year: state.to_date['year']
            }

            let formData = new FormData();
            formData.append('education_level', education.value.education_level ?? '');
            formData.append('field_study', education.value.field_study ?? '');
            formData.append('course', education.value.course ?? '');
            formData.append('school', education.value.school ?? '');
            formData.append('location', education.value.location ?? '');
            formData.append('from_date', JSON.stringify(from_date) ?? '');
            formData.append('to_date', JSON.stringify(to_date) ?? '');
            formData.append('remarks', education.value.remarks ?? '');
            formData.append('id', education.value.id ?? '');
            formData.append('user_id', state.authuser.id);
            formData.append('_method', 'PUT');
            await updateEducation(formData, props.updateId);
            isSuccess.value = true;
        }

        const backPage = () => {
            emit('add-data', 'ApplicantEducation');
        }

        onMounted( async () => {
            getEducationLevels();
            getFieldStudy();
            await getEducation(props.updateId);
            state.isLoading = false;
            state.from_date = education.value.from_date;
            state.to_date = education.value.to_date;
        });

        return {
            state,
            status,
            errors,
            studies,
            education_levels,
            education,
            updateEducation,
            getEducationLevels,
            getFieldStudy,
            getEducation,
            isSuccess,
            setEducation,
            setStudy,
            saveChanges,
            backPage
        }
    },
}
</script>