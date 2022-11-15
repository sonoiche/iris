<template>
    <div class="flex-md-row-fluid ms-lg-12" style="min-height: 80vh">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Manpower Request Settings</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="flex-row-fluid">
                            <loading v-if="page.isLoading" />
                            <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-12 mb-lg-0">
                                        <div class="form-check form-check-solid fv-row fv-plugins-icon-container">
                                            <input name="mr_notify_user" class="form-check-input" type="checkbox" v-model="form.mr_notify_user" id="mr_notify_user" />
                                            <label class="form-check-label fw-bold ps-2 fs-6" for="mr_notify_user">Notify Assigned Users</label>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-12 mb-lg-0">
                                        <BaseInput
                                            v-model="form.mr_subject"
                                            label="Email Subject"
                                            type="text"
                                            id="mr_subject"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-12 mb-lg-0">
                                        <div class="fv-row mb-0 fv-plugins-icon-container mb-3">
                                            <label for="from_name" class="form-label fs-6 fw-bolder mb-3">Email Template</label>
                                            <textarea rows="4" style="width: 100%" class="form-control form-control-lg form-control-solid" v-model="form.mr_template"></textarea>
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
            mr_notify_user: false,
            mr_subject: '',
            mr_template: ''
        });
        const isSuccess = ref(false);

        const { status, errors, config, getConfig, storeConfig } = configRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('mr_notify_user', form.mr_notify_user ?? false);
            formData.append('mr_subject', form.mr_subject ?? '');
            formData.append('mr_template', form.mr_template ?? '');
            formData.append('agency_id', page.authuser.agency_id ?? '');
            formData.append('type', 'manpower');

            await storeConfig(formData);
            isSuccess.value = true;
        }

        onMounted( async () => {
            await getConfig(page.authuser.agency_id);
            form.mr_notify_user = (config.value.mr_notify_user == 1) ? true : false;
            form.mr_subject = config.value.mr_subject;
            form.mr_template = config.value.mr_template;
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