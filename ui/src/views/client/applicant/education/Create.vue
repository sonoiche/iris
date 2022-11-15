<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add Educational Background</h3>
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
                                            :is-clear="isClear"
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
                                        <date-picker v-model="page.from_date" inputClassName="form-control form-control-solid fc-calendar" monthPicker />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">To</label>
                                        <date-picker v-model="page.to_date" inputClassName="form-control form-control-solid fc-calendar" monthPicker />
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
import educationRepo from '@/repositories/applicants/education';
import { ref, onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const page = reactive({
            from_date: '',
            to_date: '',
            addContinue: false
        });
        const { status, errors, studies, education_levels, education, storeEducation, getEducationLevels, getFieldStudy } = educationRepo();

        const isSuccess = ref(false);
        const isContinue = ref(false);
        const isClear = ref(false);

        const setEducation = (value) => {
            education.value.education_level = value.id;
        }

        const setStudy = (value) => {
            education.value.field_study = value.id;
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
                month: page.from_date['month']+1,
                year: page.from_date['year']
            }

            let to_date = {
                month: page.to_date['month']+1,
                year: page.to_date['year']
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
            formData.append('applicant_id', route.params.id);
            await storeEducation(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                education.value = [];
                state.from_date = '';
                state.to_date = '';
                isClear.value = true;
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantEducation');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantEducation');
        }

        onMounted(() => {
            getEducationLevels();
            getFieldStudy();
        });

        return {
            page,
            status,
            errors,
            studies,
            education_levels,
            education,
            storeEducation,
            getEducationLevels,
            getFieldStudy,
            isSuccess,
            isContinue,
            isClear,
            setEducation,
            setStudy,
            saveChanges,
            saveContinue,
            submitForm,
            backPage
        }
    },
}
</script>