<template>
    <div class="flex-md-row-fluid ms-lg-12" style="min-height: 80vh">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Notifications Settings</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="flex-row-fluid">
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-5">
                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path
                                            opacity="0.3"
                                            d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                            fill="currentColor"
                                        ></path>
                                        <path
                                            d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                            fill="currentColor"
                                        ></path>
                                    </svg>
                                </span>
                                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                    <div class="mb-3 mb-md-0 fw-bold">
                                        <h4 class="text-gray-900 fw-bolder">Note</h4>
                                        <div class="fs-6 text-gray-700 pe-7">IRIS will notify you of documents days to expiration. Input number of days that IRIS will use for checking.</div>
                                    </div>
                                </div>
                            </div>
                            <loading v-if="page.isLoading" />
                            <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework mt-7">
                                <div class="row mb-6">
                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.medical_day"
                                            label="Medical"
                                            type="number"
                                            id="medical_day"
                                        />
                                    </div>
                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.accreditation_day"
                                            label="Accreditation for Principal"
                                            type="number"
                                            id="accreditation_day"
                                        />
                                    </div>
                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.manpower_request_day"
                                            label="Manpower Request"
                                            type="number"
                                            id="manpower_request_day"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-4 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.processing_documents_day"
                                            label="Processing Documents"
                                            type="number"
                                            id="processing_documents_day"
                                        />
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <base-button :success="isSuccess" @submit-form="saveChanges" />
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
import { reactive, ref, onMounted } from 'vue';
import configRepo from '@/repositories/settings/agency.js';

export default {
    setup() {
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true
        });
        const form = reactive({
            medical_day: 0,
            accreditation_day: 0,
            manpower_request_day: 0,
            processing_documents_day: 0
        });
        const isSuccess = ref(false);

        const { status, errors, config, getConfig, storeConfig } = configRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('medical_day', form.medical_day ?? '');
            formData.append('accreditation_day', form.accreditation_day ?? '');
            formData.append('manpower_request_day', form.manpower_request_day ?? '');
            formData.append('processing_documents_day', form.processing_documents_day ?? '');
            formData.append('agency_id', page.authuser.agency_id ?? '');
            formData.append('type', 'notification');

            await storeConfig(formData);
            isSuccess.value = true;
        }

        onMounted( async () => {
            await getConfig(page.authuser.agency_id);
            form.medical_day = config.value.medical_day ?? 0;
            form.accreditation_day = config.value.accreditation_day ?? 0;
            form.manpower_request_day = config.value.manpower_request_day ?? 0;
            form.processing_documents_day = config.value.processing_documents_day ?? 0;
            page.isLoading = false;
        });

        return {
            page,
            form,
            isSuccess,
            saveChanges,
            status,
            errors,
            config,
            getConfig,
            storeConfig
        }
    },
}
</script>