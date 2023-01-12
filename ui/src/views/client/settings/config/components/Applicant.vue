<template>
    <div class="flex-md-row-fluid ms-lg-12" style="min-height: 80vh">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Applicant Information Settings</h3>
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
                                            <input name="auto_backup" class="form-check-input" type="checkbox" v-model="form.auto_backup" id="auto_backup" />
                                            <label class="form-check-label fw-bold ps-2 fs-6" for="auto_backup">Enable Auto Back-up</label>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
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
import userRepo from '@/repositories/settings/users.js';

export default {
    setup() {
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true
        });
        const form = reactive({
            auto_backup: false
        });
        const isSuccess = ref(false);

        const { updateBackup, status, errors } = userRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('auto_backup', form.auto_backup ?? false);
            formData.append('_method', 'PUT');

            await updateBackup(formData, page.authuser.id);
            isSuccess.value = true;
        }

        onMounted( async () => {
            form.auto_backup = (page.authuser.auto_backup == 1) ? true : false;
            page.isLoading = false;
        });

        return {
            page,
            form,
            isSuccess,
            status,
            errors,
            updateBackup,
            saveChanges
        }
    },
}
</script>