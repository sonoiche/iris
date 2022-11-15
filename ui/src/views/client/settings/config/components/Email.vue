<template>
    <div class="flex-md-row-fluid ms-lg-12" style="min-height: 80vh">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Email Configurations</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="flex-row-fluid">
                            <loading v-if="page.isLoading" />
                            <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.sender_name"
                                            label="Enter Sender Name"
                                            type="text"
                                            id="sender_name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.sender_email"
                                            label="Enter Sender Email"
                                            type="text"
                                            id="sender_email"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-12 mb-lg-0">
                                        <div class="fv-row mb-0 fv-plugins-icon-container mb-3">
                                            <label for="sender_name" class="form-label fs-6 fw-bolder mb-3">Email Signature</label>
                                            <textarea rows="4" style="width: 100%" class="form-control form-control-lg form-control-solid" v-model="form.signature"></textarea>
                                        </div>
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
            sender_name: '',
            sender_email: '',
            signature: ''
        });
        const isSuccess = ref(false);

        const { status, errors, config, getConfig, storeConfig } = configRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('sender_name', form.sender_name ?? '');
            formData.append('sender_email', form.sender_email ?? '');
            formData.append('signature', form.signature ?? '');
            formData.append('agency_id', page.authuser.agency_id ?? '');
            formData.append('type', 'email');

            await storeConfig(formData);
            isSuccess.value = true;
        }

        onMounted( async () => {
            await getConfig(page.authuser.agency_id);
            form.sender_name = config.value.sender_name;
            form.sender_email = config.value.sender_email;
            form.signature = config.value.signature;
            page.isLoading = false;
        });

        return {
            page,
            form,
            isSuccess,
            status,
            errors,
            config,
            getConfig,
            storeConfig,
            saveChanges
        }
    },
}
</script>