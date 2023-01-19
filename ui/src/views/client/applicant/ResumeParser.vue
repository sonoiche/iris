<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Resume Parser</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="row mb-6">
                                                <div class="col-lg-12 mb-4 mb-lg-0">
                                                    <label for="resume" class="form-label fs-6 fw-bolder mb-3">Upload Resume</label>
                                                    <input
                                                        type="file"
                                                        class="form-control form-control form-control-solid"
                                                        :class="{ 'is-invalid' : errors && errors['resume'] }"
                                                        id="resume"
                                                        @change="onFileChange"
                                                        multiple enctype="multipart/form-data"
                                                    />
                                                    <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['resume']">{{ errors['resume'][0] }}</label>
                                                </div>
                                            </div>
                                            <div class="row mb-6 mt-3">
                                                <div class="col-lg-12 mb-4 mb-lg-0">
                                                    <div class="d-flex justify-content-end">
                                                        <base-button :success="isSuccess" :btn-text="`Upload Resume`" @submit-form="saveChanges" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="applicant.fname != '' && isResumeDataOpen">
                                    <div class="card mb-5 mb-xl-10">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder m-0">Personal Information</h3>
                                            </div>
                                        </div>
                                        <div class="collapse show">
                                            <div class="card-body border-top p-9">
                                                <div class="row mb-6">
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.fname"
                                                            label="First Name"
                                                            type="text"
                                                            id="fname"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.mname"
                                                            label="Middle Name"
                                                            type="text"
                                                            id="mname"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.lname"
                                                            label="Last Name"
                                                            type="text"
                                                            id="lname"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.email"
                                                            label="Email Address"
                                                            type="text"
                                                            id="email"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.birthdate"
                                                            :defaultValue="applicant.birthdate"
                                                            label="Birthdate"
                                                            id="birthdate"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.mobile_number"
                                                            label="Mobile Number (Main)"
                                                            type="text"
                                                            id="mobile_number"
                                                        />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-5 mb-xl-10">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder m-0">Educations</h3>
                                            </div>
                                        </div>
                                        <div class="collapse show">
                                            <div class="card-body border-top p-9">
                                                <div v-for="education in educations" :key="education">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6 mb-4 mb-lg-0">
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
                                                        <div class="col-lg-6 mb-4 mb-lg-0">
                                                            <BaseInput
                                                                v-model="education.accreditation.inputStr"
                                                                label="Course"
                                                                type="text"
                                                                id="course"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                                            <BaseInput
                                                                v-model="education.organization"
                                                                label="University / School"
                                                                type="text"
                                                                id="school"
                                                                :errors="errors"
                                                                is-required
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-5 mb-xl-10">
                                        <div class="card-header border-0">
                                            <div class="card-title">
                                                <h3 class="fw-bolder m-0">Work Experience</h3>
                                            </div>
                                        </div>
                                        <div class="collapse show">
                                            <div class="card-body border-top p-9">
                                                <div v-for="employment in employments" :key="employment">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-6 mb-4 mb-lg-0">
                                                            <BaseInput
                                                                v-model="employment.jobTitle"
                                                                label="Position"
                                                                type="text"
                                                                id="position"
                                                                :errors="errors"
                                                                is-required
                                                            />
                                                        </div>
                                                        <div class="col-lg-6 mb-4 mb-lg-0">
                                                            <BaseInput
                                                                v-model="employment.organization"
                                                                label="Company Name"
                                                                type="text"
                                                                id="company_name"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                                            <BaseInput
                                                                v-model="employment.location"
                                                                label="Company Address"
                                                                type="text"
                                                                id="company_address"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-6 mt-3">
                                        <div class="col-lg-12 mb-4 mb-lg-0">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-outline-danger" @click="cancelParser" style="margin-right: 10px">Cancel</button>
                                                <base-button :success="isSuccess" @submit-form="submitApplicant" />
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
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import applicantRepo from '@/repositories/applicants/applicant';
import educationRepo from '@/repositories/applicants/education';

export default {
    setup(props) {
        const { status, errors, resumefile, resume_id, uploadResumeParser, getResumeParser, resumeData, deleteResumeParser } = applicantRepo();
        const { education_levels } = educationRepo();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            education: []
        });
        const isSuccess = ref(true);
        const resume = ref('');
        const applicant = ref({
            fname: '',
            mname: '',
            lname: '',
            email: ''
        });
        const educations = ref([]);
        const employments = ref([]);
        const isResumeDataOpen = ref(false);

        // resume parser
        const { AffindaCredential, AffindaAPI } = require("@affinda/affinda");
        const credential = new AffindaCredential(process.env.VUE_APP_AFFINDA_API_KEY);
        const client = new AffindaAPI(credential);

        const onFileChange = (e) => {
            const file = e.target.files[0];
            resume.value = file;
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('resume', resume.value ?? '');
            formData.append('user_id', state.authuser.id);

            await uploadResumeParser(formData);
            if(status.value == 200) {
                client.createResume({ url: resumefile.value }).then( async (result) => {
                    let response = await axios.post(`client/resume-parser`, {
                        content: JSON.stringify(result.data),
                        user_id: state.authuser.id,
                        resume_id: resume_id.value
                    });

                    if(response.status == 200) {
                        isSuccess.value = true;
                    }
                }).catch((err) => {
                    console.log("An error occurred:");
                    console.error(err);
                });
            }
        }

        const setEducation = (evt) => {
            state.education.push(evt);
        }

        const submitApplicant = () => {
            console.log(applicant.value);
            console.log(educations.value);
            console.log(employments.value);
        }

        const cancelParser = () => {

        }

        onMounted( async () => {
            await getResumeParser(state);
            applicant.value.fname = resumeData.value.name?.first;
            applicant.value.mname = resumeData.value.name?.middle;
            applicant.value.lname = resumeData.value.name?.last;
            applicant.value.email = (resumeData.value.emails.length) ? resumeData.value.emails[0] : '';
            applicant.value.mobile_number = (resumeData.value.phoneNumbers.length) ? resumeData.value.phoneNumbers[0] : '';
            educations.value = resumeData.value.education;
            employments.value = resumeData.value.workExperience;
            isResumeDataOpen.value = (resumeData.value.name.length);
        });

        return {
            status,
            errors,
            state,
            resume,
            resume_id,
            resumefile,
            education_levels,
            isSuccess,
            uploadResumeParser,
            onFileChange,
            saveChanges,
            applicant,
            educations,
            employments,
            getResumeParser,
            resumeData,
            setEducation,
            submitApplicant,
            deleteResumeParser,
            cancelParser,
            isResumeDataOpen
        }
    }
}
</script>