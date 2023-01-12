<template>
    <div>
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="row mb-6">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <BaseInput
                        v-model="applicant.position_applied"
                        label="Position Applied"
                        type="text"
                        id="position_applied"
                        :errors="errors"
                        is-required
                    />
                </div>
            </div>
        </div>
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
                <BaseDatePicker
                    v-model="applicant.birthdate"
                    :defaultValue="applicant.birthdate"
                    label="Birthdate"
                    id="birthdate"
                    :errors="errors"
                    is-required
                />
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <BaseInput
                    v-model="applicant.mobile_number"
                    label="Mobile Number (Main)"
                    type="text"
                    id="mobile_number"
                    :errors="errors"
                    is-required
                />
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="fv-row mb-0 fv-plugins-icon-container">
                    <label for="resume" class="form-label fs-6 fw-bolder mb-3">Attach Resume</label>
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
        </div>
        <div class="row mb-6">
            <div class="col-lg-12 mb-4 mb-lg-0">
                <BaseSelect
                    label="Keywords (used for applicant searching)"
                    :placeholder="`Enter Keywords`"
                    :id="`keywords`"
                    :defaultValue="{}"
                    :multiple="true"
                    :taggable="true"
                    :clear="isClear"
                    @select-value="setKeywords"
                    @remove-value="removeKeywords"
                />
                <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['keywords']">{{ errors['keywords'][0] }}</label>
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
</template>

<script>
import { ref, reactive } from '@vue/reactivity';
import { useRouter } from 'vue-router';
import $ from 'jquery';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    setup() {
        const router = useRouter();
        const { status, errors, applicant, encodeApplicant } = applicantRepo();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const isSuccess = ref(true);
        const isContinue = ref(false);
        const isClear = ref(false);
        const resume = ref('');
        const keywords = ref([]);

        const onFileChange = (e) => {
            const file = e.target.files[0];
            resume.value = file;
        }

        const setKeywords = (value) => {
            keywords.value.push(value[0].name);
        }

        const removeKeywords = (value) => {
            keywords.value.splice(keywords.value.indexOf(value), 1);
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            await submitForm();

            if(status.value == 200) {
                applicant.value = [];
                isClear.value = true;
                window.$ = window.jQuery = require('jquery');
                $('#resume').val('');
                isSuccess.value = true;
            }
        }

        const saveContinue = async () => {
            isContinue.value = false;
            await submitForm();

            if(status.value == 200) {
                setTimeout(() => {
                    router.push({
                        name: 'client.applicant.show',
                        params: {
                            id: applicant.value.applicant_number
                        }
                    });
                }, 1000);
            }
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('position_applied', applicant.value.position_applied ?? '');
            formData.append('fname', applicant.value.fname ?? '');
            formData.append('mname', applicant.value.mname ?? '');
            formData.append('lname', applicant.value.lname ?? '');
            formData.append('mobile_number', applicant.value.mobile_number ?? '');
            formData.append('birthdate', applicant.value.birthdate ? new Date(applicant.value.birthdate).toISOString() : '');
            formData.append('birthplace', applicant.value.birthplace ?? '');
            formData.append('keywords', keywords.value ?? '');
            formData.append('resume', resume.value ?? '');
            formData.append('user_id', state.authuser.id);
            await encodeApplicant(formData);

            if(status.value !== 200) {
                isSuccess.value = true;
                isContinue.value = true;
            }
        }

        return {
            state,
            status,
            errors,
            applicant,
            encodeApplicant,
            saveChanges,
            isSuccess,
            isContinue,
            isClear,
            onFileChange,
            resume,
            setKeywords,
            keywords,
            removeKeywords,
            submitForm,
            saveContinue
        }
    },
}
</script>