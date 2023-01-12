<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl" v-if="!isApplicantSourceGenerated">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Applicant Source</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseSelect 
                                                            label="Source of Applicant"
                                                            :options="sources"
                                                            :placeholder="`Select Source`"
                                                            :id="`source_id`"
                                                            @select-value="setApplicantSource"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.position_applied"
                                                            label="Position Applied"
                                                            type="text"
                                                            id="position_applied"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="form-label fs-6 fw-bolder">Years Experience</label>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.yrs_min"
                                                            type="number"
                                                            label="Min"
                                                            id="yrs_min"
                                                            min="1"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.yrs_max"
                                                            type="number"
                                                            label="Max"
                                                            id="yrs_max"
                                                            min="1"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.location"
                                                            label="Location"
                                                            type="text"
                                                            id="location"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.keyword"
                                                            label="Keyword"
                                                            type="text"
                                                            id="keyword"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <label for="direct_hire" class="form-label fs-6 fw-bolder mb-3">Gender</label>
                                                        <div class="d-flex align-items-center" style="height: 40px;">
                                                            <div class="form-check form-check-custom form-check-solid mr-15">
                                                                <input class="form-check-input" type="radio" v-model="form.gender" value="any" id="any"/>
                                                                <label class="form-check-label" for="any">
                                                                    Any
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-custom form-check-solid mr-15">
                                                                <input class="form-check-input" type="radio" v-model="form.gender" value="male" id="male"/>
                                                                <label class="form-check-label" for="male">
                                                                    Male
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="radio" v-model="form.gender" value="female" id="female"/>
                                                                <label class="form-check-label" for="female">
                                                                    Female
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="form.date_applied"
                                                            label="Date Applied"
                                                            id="date_applied"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="form-label fs-6 fw-bolder">Education</label>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.school"
                                                            type="text"
                                                            label="School"
                                                            id="school"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.course"
                                                            type="text"
                                                            label="Course"
                                                            id="course"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <label class="form-label fs-6 fw-bolder">Skills</label>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput 
                                                            v-model="form.skill"
                                                            type="text"
                                                            label="Skill Name"
                                                            id="skill"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Level of Proficiency"
                                                            :options="skill_levels"
                                                            :placeholder="`Select Skill Level`"
                                                            id="skill_level"
                                                            @select-value="setSkillLevel"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6 mt-3">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <div class="d-flex justify-content-end">
                                                            <base-button :success="isSuccess" :btn-text="`Source Applicant`" @submit-form="generateSource " />
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
                    <ApplicantSourceList v-if="isApplicantSourceGenerated" :applicants="applicants" @reset-page="refreshPage" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import sourceRepo from '@/repositories/settings/source';
import skillRepo from '@/repositories/applicants/skill';
import reportRepo from '@/repositories/applicants/reports';
import ApplicantSourceList from '@/views/client/reports/ApplicantSource';

export default {
    components: {
        ApplicantSourceList
    },
    setup() {
        const form = reactive({
            source_id: '',
            position_applied: '',
            skill_level: '',
            yrs_min: '',
            yrs_max: '',
            location: '',
            keyword: '',
            gender: '',
            date_applied: '',
            school: '',
            course: '',
            skill: ''
        });
        const { sources, getSourceOptions } = sourceRepo();
        const { skill_levels, getSkillLevels } = skillRepo();
        const { status, applicants, generateApplicantSource } = reportRepo();
        
        const isSuccess = ref(false);
        const isApplicantSourceGenerated = ref(false);

        const setApplicantSource = (value) => {
            form.source_id = value;
        }

        const setSkillLevel = (value) => {
            form.skill_level = value.id;
        }

        const generateSource  = async () => {
            let formData = new FormData();
            formData.append('date_applied', form.date_applied ? new Date(form.date_applied).toISOString() : '');
            formData.append('source_id', form.source_id ? form.source_id : '');
            formData.append('position_applied', form.position_applied ? form.position_applied : '');
            formData.append('skill_level', form.skill_level ? form.skill_level : '');
            formData.append('yrs_min', form.yrs_min ? form.yrs_min : '');
            formData.append('yrs_max', form.yrs_max ? form.yrs_max : '');
            formData.append('location', form.location ? form.location : '');
            formData.append('keyword', form.keyword ? form.keyword : '');
            formData.append('gender', form.gender ? form.gender : '');
            formData.append('school', form.school ? form.school : '');
            formData.append('course', form.course ? form.course : '');
            formData.append('skil', form.skill ? form.skill : '');

            await generateApplicantSource(formData);

            if(status.value !== 200) {
                isSuccess.value = true;
            } else {
                isApplicantSourceGenerated.value = true;
            }
        }

        const refreshPage = () => {
            applicants.value = [];
            form.source_id = '';
            form.position_applied = '';
            form.skill_level = '';
            form.yrs_min = '';
            form.yrs_max = '';
            form.location = '';
            form.keyword = '';
            form.gender = '';
            form.date_applied = '';
            form.school = '';
            form.course = '';
            form.skill = '';
            isApplicantSourceGenerated.value = false;
        }

        onMounted(() => {
            getSourceOptions();
            getSkillLevels();
        });

        return {
            form,
            sources,
            getSourceOptions,
            skill_levels,
            getSkillLevels,
            isSuccess,
            status,
            applicants,
            generateApplicantSource,
            setApplicantSource,
            setSkillLevel,
            generateSource,
            isApplicantSourceGenerated,
            refreshPage
        }
    }
}
</script>

<style scoped>
.mr-15 {
    margin-right: 15px !important;
}
</style>