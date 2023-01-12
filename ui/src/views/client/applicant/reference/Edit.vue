<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Update Reference</h3>
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
import referenceRepo from '@/repositories/applicants/reference';
import { ref, reactive, onMounted } from 'vue';

export default {
    props: {
        updateId: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            addContinue: false,
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const { status, errors, reference, updateReference, getReference } = referenceRepo();

        const isSuccess = ref(false);

        const saveChanges = () => {
            isSuccess.value = false;
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
            formData.append('id', reference.value.id ?? '');
            formData.append('user_id', state.authuser.id);
            formData.append('_method', 'PUT');
            await updateReference(formData, props.updateId);
            isSuccess.value = true;
        }

        const backPage = () => {
            emit('add-data', 'ApplicantReference');
        }

        onMounted( async () => {
            await getReference(props.updateId);
        });

        return {
            state,
            status,
            errors,
            reference,
            updateReference,
            getReference,
            isSuccess,
            saveChanges,
            submitForm,
            backPage
        }
    },
}
</script>