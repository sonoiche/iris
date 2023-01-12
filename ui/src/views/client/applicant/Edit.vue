<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 90%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Update Applicant Information</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-lg-0">
                                                        <div class="row">
                                                            <div class="col-lg-7 mb-4 mb-lg-0">
                                                                <BaseInput
                                                                    v-model="applicant.position_applied"
                                                                    label="Position Applied"
                                                                    type="text"
                                                                    id="position_applied"
                                                                />
                                                            </div>
                                                            <div class="col-lg-3 mb-4 mb-lg-0">
                                                                <BaseInput
                                                                    v-model="applicant.yrs_of_exp"
                                                                    label="Year of Experience"
                                                                    type="text"
                                                                    id="experience"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Source"
                                                            :options="sources"
                                                            :placeholder="`Select Applicant Source`"
                                                            :id="`source_id`"
                                                            :defaultValue="{ id: applicant.source_id, name: applicant.source_name }"
                                                            @select-value="setSource"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.date_applied"
                                                            :defaultValue="applicant.date_applied"
                                                            label="Date Applied"
                                                            id="date_applied"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.expected_salary"
                                                            label="Expected Salary (in peso)"
                                                            type="number"
                                                            id="expected_salary"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
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
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.fname"
                                                            label="First Name"
                                                            type="text"
                                                            id="fname"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.mname"
                                                            label="Middle Name"
                                                            type="text"
                                                            id="mname"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.lname"
                                                            label="Last Name"
                                                            type="text"
                                                            id="lname"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.suffix"
                                                            label="Suffix (Jr., Sr., III)"
                                                            type="text"
                                                            id="suffix"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.birthdate"
                                                            :defaultValue="applicant.birthdate"
                                                            label="Birthdate"
                                                            id="birthdate"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
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
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.height"
                                                            label="Height"
                                                            type="text"
                                                            id="height"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.weight"
                                                            label="Weight"
                                                            type="text"
                                                            id="weight"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.birthplace"
                                                            label="Birthplace"
                                                            type="text"
                                                            id="birthplace"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Marital Status"
                                                            :options="marital_status"
                                                            :placeholder="`Select Marital Status`"
                                                            :id="`marital_status`"
                                                            :defaultValue="{ id: applicant.marital_status, name: applicant.marital_status }"
                                                            @select-value="setMaritalStatus"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Nationality"
                                                            :options="nationalities"
                                                            :placeholder="`Select Nationality`"
                                                            :id="`nationality_id`"
                                                            :defaultValue="{ id: applicant.nationality_id, name: applicant.nationality_name }"
                                                            @select-value="setNationality"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.address"
                                                            label="Present Address"
                                                            type="text"
                                                            id="address"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.city"
                                                            label="City"
                                                            type="text"
                                                            id="city"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.province"
                                                            label="Province"
                                                            type="text"
                                                            id="province"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.postal_code"
                                                            label="Postal Code"
                                                            type="text"
                                                            id="postal_code"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Religion"
                                                            :options="religions"
                                                            :placeholder="`Select Religion`"
                                                            :id="`religion`"
                                                            :defaultValue="{ id: applicant.religion, name: applicant.religion }"
                                                            @select-value="setReligion"
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
                                                    <div class="col-lg-5 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.language_spoken"
                                                            label="Language Spoken & Written"
                                                            type="text"
                                                            id="language_spoken"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Keywords (used for applicant searching)"
                                                            :placeholder="`Enter Keywords`"
                                                            :id="`keywords`"
                                                            :defaultValue="keywordsObject"
                                                            :multiple="true"
                                                            :taggable="true"
                                                            @select-value="setKeywords"
                                                            @remove-value="removeKeywords"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Contact Details</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.email"
                                                            label="Email Address"
                                                            type="email"
                                                            id="email"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.landline"
                                                            label="Landline"
                                                            type="text"
                                                            id="landline"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.mobile_number"
                                                            label="Mobile Number (Main)"
                                                            type="text"
                                                            id="mobile_number"
                                                            :errors="errors"
                                                            is-required
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.alt_mobile_number"
                                                            label="Mobile Number (alternate)"
                                                            type="text"
                                                            id="alt_mobile_number"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Passport Details</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.passport_number"
                                                            label="Passport Number"
                                                            type="text"
                                                            id="passport_number"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.passport_place_issued"
                                                            label="Place Issued"
                                                            type="text"
                                                            id="passport_place_issued"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                                            <label for="passport_file" class="form-label fs-6 fw-bolder mb-3">Attachment</label>
                                                            <input
                                                                type="file"
                                                                class="form-control form-control form-control-solid"
                                                                :class="{ 'is-invalid' : errors && errors['passport_file'] }"
                                                                id="passport_file"
                                                                @change="onPassportFileChange"
                                                                multiple enctype="multipart/form-data"
                                                            />
                                                            <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['passport_file']">{{ errors['passport_file'][0] }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.passport_date_submitted"
                                                            :defaultValue="applicant.passport_date_submitted"
                                                            label="Date Submitted"
                                                            id="date_submitted"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.passport_date_issued"
                                                            :defaultValue="applicant.passport_date_issued"
                                                            label="Date Issue"
                                                            id="date_issued"
                                                        />
                                                    </div>
                                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                                        <BaseDatePicker
                                                            v-model="applicant.passport_date_expiry"
                                                            :defaultValue="applicant.passport_date_expiry"
                                                            label="Date Expiry"
                                                            id="date_expiry"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Other Information</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.sss_number"
                                                            label="GSIS / SSS Number"
                                                            type="text"
                                                            id="sss_number"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.tin_number"
                                                            label="TIN Number"
                                                            type="text"
                                                            id="tin_number"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.philhealth"
                                                            label="Philhealth"
                                                            type="text"
                                                            id="philhealth"
                                                        />
                                                    </div>
                                                    <div class="col-lg-3 mb-4 mb-lg-0">
                                                        <BaseInput
                                                            v-model="applicant.pagibig"
                                                            label="Pag-Ibig Number"
                                                            type="text"
                                                            id="pagibig"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                                        <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="applicant.remarks"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-6 mt-3">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <div class="d-flex justify-content-end">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';
import applicantRepo from '@/repositories/applicants/applicant';
import sourceRepo from '@/repositories/settings/source';
import nationalityRepo from '@/repositories/settings/nationality';

export default {
    setup() {
        const route = useRoute();
        const { status, errors, applicant, getApplicant, updateApplicant } = applicantRepo();
        const { sources, getSources } = sourceRepo();
        const { nationalities, getNationalities } = nationalityRepo();

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
        const marital_status = [
            { id: 'Single', name: 'Single' },
            { id: 'Married', name: 'Married' },
            { id: 'Widowed', name: 'Widowed' },
            { id: 'Separated', name: 'Separated' }
        ];
        const religions = [
            { id: 'Christians', name: 'Christians' },
            { id: 'Catholic', name: 'Catholic' },
            { id: 'Buddhism', name: 'Buddhism' },
            { id: 'Hinduism', name: 'Hinduism' },
            { id: 'Islam', name: 'Islam' }
        ];
        const keywords = ref([]);
        const resume = ref('');
        const passport_file = ref('');
        const isSuccess = ref(false);
        const sourceOptions = ref([]);
        const keywordsObject = ref([]);

        const setAvailability = (value) => {
            applicant.value.availability = value;
        }

        const setSource = (value) => {
            applicant.value.source_id = value.id;
            applicant.value.source_name = value.name;
        }

        const setMaritalStatus = (value) => {
            applicant.value.marital_status = value.id;
        }

        const setNationality = (value) => {
            applicant.value.nationality_id = value.id;
            applicant.value.nationality_name = value.name;
        }

        const setReligion = (value) => {
            applicant.value.religion = value.id;
        }

        const setKeywords = (value) => {
            if(value) {
                keywords.value.push(JSON.stringify(value));
            }
        }

        const removeKeywords = (value) => {
            keywords.value.splice(keywords.value.indexOf(value), 1);
        }

        const saveChanges = async () => {
            isSuccess.value = false;
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
            formData.append('source_id', applicant.value.source_id ?? '');
            formData.append('suffix', applicant.value.suffix ?? '');
            formData.append('height', applicant.value.height ?? '');
            formData.append('weight', applicant.value.weight ?? '');
            formData.append('marital_status', applicant.value.marital_status ?? '');
            formData.append('nationality_id', applicant.value.nationality_id ?? '');
            formData.append('religion', applicant.value.religion ?? '');
            formData.append('passport_number', applicant.value.passport_number ?? '');
            formData.append('passport_place_issued', applicant.value.passport_place_issued ?? '');
            formData.append('passport_file', passport_file.value ?? '');
            formData.append('passport_date_issued', applicant.value.passport_date_issued ? new Date(applicant.value.passport_date_issued).toISOString() : '');
            formData.append('passport_date_submitted', applicant.value.passport_date_submitted ? new Date(applicant.value.passport_date_submitted).toISOString() : '');
            formData.append('passport_date_expiry', applicant.value.passport_date_expiry ? new Date(applicant.value.passport_date_expiry).toISOString() : '');
            formData.append('sss_number', applicant.value.sss_number ?? '');
            formData.append('tin_number', applicant.value.tin_number ?? '');
            formData.append('philhealth', applicant.value.philhealth ?? '');
            formData.append('pagibig', applicant.value.pagibig ?? '');
            formData.append('remarks', applicant.value.remarks ?? '');
            formData.append('position_applied', applicant.value.position_applied ?? '');
            formData.append('yrs_of_exp', applicant.value.yrs_of_exp ?? '');
            formData.append('user_id', state.authuser.id);
            formData.append('_method', 'PUT');

            await updateApplicant(formData, applicant.value.id);

            if(status.value !== 200) {
                isSuccess.value = true;
            }

            isSuccess.value = true;
        }

        const onFileChange = (e) => {
            const file = e.target.files[0];
            resume.value = file;
        }

        const onPassportFileChange = (e) => {
            const file = e.target.files[0];
            passport_file.value = file;
        }

        const initRequireData = async () => {
            await getSources();
            sources.value.map((item) => {
                return {
                    id: item.id,
                    name: item.name
                }
            });
            await getNationalities();
            nationalities.value.map((item) => {
                return {
                    id: item.id,
                    name: item.name
                }
            });
        }

        onMounted( async () => {
            initRequireData();
            await getApplicant(route.params.id);
            keywordsObject.value = JSON.parse(applicant.value.keywords);
            keywordsObject.value.map((item) => {
                return {
                    id: item.id,
                    name: item.name
                }
            });
        });

        return {
            state,
            status,
            errors,
            applicant,
            getApplicant,
            updateApplicant,
            availabilities,
            isSuccess,
            setAvailability,
            setSource,
            setKeywords,
            removeKeywords,
            saveChanges,
            keywords,
            resume,
            onFileChange,
            sources,
            getSources,
            sourceOptions,
            initRequireData,
            marital_status,
            setMaritalStatus,
            setNationality,
            nationalities,
            getNationalities,
            religions,
            setReligion,
            onPassportFileChange,
            passport_file,
            keywordsObject
        }
    },
}
</script>

<style scoped>
.mr-15 {
    margin-right: 15px !important;
}
</style>