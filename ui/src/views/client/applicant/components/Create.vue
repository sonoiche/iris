<template>
    <div>
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="row mb-6">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseDatePicker
                        v-model="applicant.date_applied"
                        :defaultValue="applicant.date_applied"
                        label="Date Applied"
                        id="date_applied"
                        :errors="errors"
                        is-required
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.expected_salary"
                        label="Expected Salary (in peso)"
                        type="number"
                        id="expected_salary"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseSelect 
                        label="Availability"
                        :options="availabilities"
                        :placeholder="`Select Availability`"
                        :id="`availability`"
                        :defaultValue="{ id: applicant.availability, name: applicant.availability }"
                        @select-value="setAvailability"
                    />
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
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.mname"
                        label="Middle Name"
                        type="text"
                        id="mname"
                        @input="autoBackup"
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
                        @input="autoBackup"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.landline"
                        label="Landline"
                        type="text"
                        id="landline"
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
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.alt_mobile_number"
                        label="Mobile Number (alternate)"
                        type="text"
                        id="alt_mobile_number"
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
                        @update:modelValue="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.email"
                        label="Email Address"
                        type="email"
                        id="email"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <label for="job_order_type" class="form-label fs-6 fw-bolder mb-3">Gender</label>
                    <div class="d-flex align-items-center" style="height: 40px;">
                        <div class="form-check form-check-custom form-check-solid mr-15">
                            <input class="form-check-input" type="radio" v-model="applicant.gender" value="Male" id="male"/>
                            <label class="form-check-label" for="male">
                                Male
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" v-model="applicant.gender" value="Female" id="female"/>
                            <label class="form-check-label" for="female">
                                Female
                            </label>
                        </div>
                        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors.gender">{{ errors.gender[0] }}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.address"
                        label="Present Address"
                        type="text"
                        id="address"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.city"
                        label="City"
                        type="text"
                        id="city"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.province"
                        label="Province"
                        type="text"
                        id="province"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.postal_code"
                        label="Postal Code"
                        type="text"
                        id="postal_code"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.birthplace"
                        label="Birthplace"
                        type="text"
                        id="birthplace"
                        @input="autoBackup"
                    />
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="applicant.language_spoken"
                        label="Language Spoken & Written"
                        type="text"
                        id="language_spoken"
                        @input="autoBackup"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-5 mb-4 mb-lg-0">
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
</template>

<script>
import { ref, reactive, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import $ from 'jquery';
import _debounce from 'lodash/debounce';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    setup() {
        const router = useRouter();
        const { status, errors, applicant, storeApplicant } = applicantRepo();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const availabilities = [
            { id: 'Immediate', name: 'Immediate' },
            { id: '1 Week', name: '1 Week' },
            { id: '2 Weeks', name: '2 Weeks' },
            { id: '3 Weeks', name: '3 Weeks' },
            { id: '1 Month', name: '1 Month' },
            { id: '2 Months', name: '2 Months' },
            { id: '3 Months', name: '3 Months' }
        ];
        const keywords = ref([]);
        const resume = ref('');
        const isSuccess = ref(false);
        const isContinue = ref(false);
        const isClear = ref(false);

        const setAvailability = (value) => {
            applicant.value.availability = value.id;
            autoBackup();
        }

        const setKeywords = (value) => {
            keywords.value.push(value);
            autoBackup();
        }

        const removeKeywords = (value) => {
            keywords.value.splice(keywords.value.indexOf(value), 1);
            autoBackup();
        }

        const saveChanges = async () => {            
            isSuccess.value = false;
            await submitForm();
            
            if(status.value == 200) {
                applicant.value = [];
                applicant.value.date_applied = new Date();
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
                localStorage.removeItem('applicant');
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
            formData.append('date_applied', applicant.value.date_applied ? new Date(applicant.value.date_applied).toISOString() : '');
            formData.append('expected_salary', applicant.value.expected_salary ?? '');
            formData.append('availability', applicant.value.availability ?? '');
            formData.append('fname', applicant.value.fname ?? '');
            formData.append('mname', applicant.value.mname ?? '');
            formData.append('lname', applicant.value.lname ?? '');
            formData.append('landline', applicant.value.landline ?? '');
            formData.append('mobile_number', applicant.value.mobile_number ?? '');
            formData.append('alt_mobile_number', applicant.value.alt_mobile_number ?? '');
            formData.append('birthdate', applicant.value.birthdate ? new Date(applicant.value.birthdate).toISOString() : '');
            formData.append('email', applicant.value.email ?? '');
            formData.append('gender', applicant.value.gender ?? '');
            formData.append('address', applicant.value.address ?? '');
            formData.append('city', applicant.value.city ?? '');
            formData.append('province', applicant.value.province ?? '');
            formData.append('postal_code', applicant.value.postal_code ?? '');
            formData.append('birthplace', applicant.value.birthplace ?? '');
            formData.append('language_spoken', applicant.value.language_spoken ?? '');
            formData.append('keywords', keywords.value ?? '');
            formData.append('resume', resume.value ?? '');
            formData.append('user_id', state.authuser.id);

            await storeApplicant(formData);

            if(status.value !== 200) {
                isSuccess.value = true;
                isContinue.value = true;
            } else {
                localStorage.removeItem('applicant');
            }
        }

        const onFileChange = (e) => {
            const file = e.target.files[0];
            resume.value = file;
        }

        const autoBackup = _debounce(function (event) {
            if(state.authuser.auto_backup) {
                let form = {
                    date_applied: applicant.value.date_applied,
                    expected_salary: applicant.value.expected_salary,
                    expected_salary: applicant.value.expected_salary,
                    availability: applicant.value.availability,
                    fname: applicant.value.fname,
                    mname: applicant.value.mname,
                    lname: applicant.value.lname,
                    landline: applicant.value.landline,
                    mobile_number: applicant.value.mobile_number,
                    alt_mobile_number: applicant.value.alt_mobile_number,
                    birthdate: applicant.value.birthdate,
                    email: applicant.value.email,
                    gender: applicant.value.gender,
                    address: applicant.value.address,
                    city: applicant.value.city,
                    province: applicant.value.province,
                    postal_code: applicant.value.postal_code,
                    birthplace: applicant.value.birthplace,
                    language_spoken: applicant.value.language_spoken
                }
                
                localStorage.setItem('applicant', JSON.stringify(form));
            }
        }, 800);

        watch(() => applicant.value.gender, () => {
            autoBackup();
        });

        onMounted(() => {
            if(localStorage.getItem('applicant') !== null) {
                applicant.value = JSON.parse(localStorage.getItem('applicant'));
            }
            applicant.value.date_applied = new Date();
        });

        return {
            state,
            status,
            errors,
            applicant,
            storeApplicant,
            availabilities,
            isSuccess,
            isContinue,
            setAvailability,
            setKeywords,
            removeKeywords,
            saveChanges,
            saveContinue,
            keywords,
            resume,
            onFileChange,
            isClear,
            autoBackup
        }
    },
}
</script>

<style scoped>
.mr-15 {
    margin-right: 15px !important;
}
</style>