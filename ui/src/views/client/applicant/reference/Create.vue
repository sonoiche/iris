<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add Training & Seminar</h3>
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
                                        <BaseInput
                                            v-model="reference.name"
                                            label="Fullname"
                                            type="text"
                                            id="name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="reference.position"
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
                                            v-model="reference.company"
                                            label="Company"
                                            type="text"
                                            id="company"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="reference.contact_number"
                                            label="Contact Number"
                                            type="text"
                                            id="contact_number"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="reference.email"
                                            label="Email Address"
                                            type="email"
                                            id="email"
                                            :errors="errors"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="reference.relationship"
                                            label="Relationship"
                                            type="text"
                                            id="relationship"
                                            :errors="errors"
                                            is-required
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import referenceRepo from '@/repositories/applicants/reference';
import { ref, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            date_issue: '',
            date_expiry: '',
            addContinue: false
        });
        const { status, errors, reference, storeReference } = referenceRepo();

        const isSuccess = ref(false);
        const isContinue = ref(false);

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
            let formData = new FormData();
            formData.append('name', reference.value.name ?? '');
            formData.append('position', reference.value.position ?? '');
            formData.append('company', reference.value.company ?? '');
            formData.append('contact_number', reference.value.contact_number ?? '');
            formData.append('email', reference.value.email ?? '');
            formData.append('relationship', reference.value.relationship ?? '');
            formData.append('applicant_id', route.params.id);
            await storeReference(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                reference.value = [];
                state.date_issue = '';
                state.date_expiry = '';
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantReference');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantReference');
        }

        return {
            state,
            status,
            errors,
            reference,
            storeReference,
            isSuccess,
            isContinue,
            saveChanges,
            saveContinue,
            submitForm,
            backPage
        }
    },
}
</script>