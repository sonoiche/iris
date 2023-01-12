<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 70%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title d-flex justify-content-between w-full">
                                            <h3 class="fw-bolder m-0">Schedule an Interview</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <BaseSelect
                                                        label="Principal"
                                                        :options="principals"
                                                        :placeholder="`Select Principal`"
                                                        id="principal_id"
                                                        :defaultValue="{ id: interview.principal_id, name: interview.principal_name }"
                                                        :errors="errors"
                                                        is-required
                                                        @select-value="setPrincipal"
                                                        @remove-value="removePrincipal"
                                                    />
                                                </div>
                                                <div class="col-md-6">
                                                    <BaseSelect
                                                        label="Manpower Request"
                                                        :options="joborderOptions"
                                                        :placeholder="`Select Manpower Request`"
                                                        id="position_id"
                                                        :defaultValue="{ id: interview.position_id, name: interview.position_name }"
                                                        :errors="errors"
                                                        is-required
                                                        @select-value="setJobOrder"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <BaseDatePicker
                                                        v-model="interview.date"
                                                        :defaultValue="interview.date"
                                                        label="Interview Date"
                                                        id="interview_date"
                                                        :errors="errors"
                                                        is-required
                                                    />
                                                </div>
                                                <div class="col-md-6">
                                                    <BaseInput
                                                        v-model="interview.time"
                                                        label="Interview Time"
                                                        type="time"
                                                        id="time"
                                                        :errors="errors"
                                                        is-required
                                                    />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <BaseInput
                                                        v-model="interview.venue"
                                                        label="Interview Venue"
                                                        type="text"
                                                        id="venue"
                                                        :errors="errors"
                                                        is-required
                                                    />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                                    <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="interview.remarks"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <BaseSelect
                                                        label="Applicant Name"
                                                        :placeholder="`Enter Applicant Name`"
                                                        :id="`applicant-name`"
                                                        :options="applicantOptions"
                                                        :multiple="true"
                                                        @select-value="setApplicants"
                                                        @remove-value="removeApplicants"
                                                    />
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12 d-flex justify-content-end">
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
</template>

<script>
import { computed, onMounted, ref, reactive } from '@vue/runtime-core';
import { useRouter } from 'vue-router';
import principalRepo from '@/repositories/employer/principal';
import interviewRepo from '@/repositories/applicants/interview';
import joborderRepo from '@/repositories/employer/joborder';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    setup() {
        const router = useRouter();
        const { principals, getSelectPrincipal } = principalRepo();
        const { interview, storeInterview, status, errors } = interviewRepo();
        const { joborders, getJobOrderPositions } = joborderRepo();
        const { applicants, getApplicants } = applicantRepo();

        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });

        const selectedApplicants = ref([]);
        const isSuccess = ref(true);

        const joborderOptions = computed(() => {
            const arr_joborders = [];
            joborders.value.forEach(item => {
                if(item.principal_id == interview.value.principal_id) {
                    arr_joborders.push({
                        id: item.position_id,
                        name: `${item.job_order_number} - ${item.position_title}`
                    });
                }
            });

            return arr_joborders;
        });

        const applicantOptions = computed(() => {
            const arr_applicants = [];
            applicants.value.forEach(item => {
                arr_applicants.push({
                    id: item.applicant_number,
                    name: item.fullname
                });
            });

            return arr_applicants;
        });

        const setPrincipal = (value) => {
            interview.value.principal_id = value.id;
        }

        const removePrincipal = () => {
            interview.value.principal_id = 0;
        }

        const setJobOrder = (value) => {
            interview.value.position_id = value.id;
        }

        const setApplicants = (value) => {
            selectedApplicants.value.push(value.id);
        }

        const removeApplicants = (value) => {
            selectedApplicants.value.splice(selectedApplicants.value.indexOf(value), 1);
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('principal_id', interview.value.principal_id ?? '');
            formData.append('position_id', interview.value.position_id ?? '');
            formData.append('interview_date', interview.value.date ? new Date(interview.value.date).toISOString() : '');
            formData.append('time', interview.value.time ?? '');
            formData.append('venue', interview.value.venue ?? '');
            formData.append('remarks', interview.value.remarks ?? '');
            formData.append('applicant_ids', selectedApplicants.value ?? '');
            formData.append('user_id', state.authuser.id);
            await storeInterview(formData);

            isSuccess.value = true;
            if(status.value == 200) {
                router.push({
                    name: 'client.interview'
                });
            }
        }

        onMounted(() => {
            getSelectPrincipal();
            getJobOrderPositions();
            getApplicants();
        });

        return {
            state,
            principals,
            getSelectPrincipal,
            setPrincipal,
            interview,
            storeInterview,
            status,
            errors,
            getJobOrderPositions,
            joborders,
            joborderOptions,
            removePrincipal,
            setJobOrder,
            applicantOptions,
            getApplicants,
            setApplicants,
            removeApplicants,
            applicants,
            saveChanges,
            isSuccess
        }
    },
}
</script>