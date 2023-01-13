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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue';
import axios from 'axios';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    setup(props) {
        const { status, errors, resumefile, resume_id, uploadResumeParser } = applicantRepo();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const isSuccess = ref(true);
        const resume = ref('');

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

        return {
            status,
            errors,
            state,
            resume,
            resume_id,
            resumefile,
            isSuccess,
            uploadResumeParser,
            onFileChange,
            saveChanges
        }
    }
}
</script>