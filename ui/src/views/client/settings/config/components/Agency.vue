<template>
    <div class="flex-md-row-fluid ms-lg-12" style="min-height: 80vh">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Agency Details</h3>
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
                                            v-model="form.agency_name"
                                            label="Enter Agency Name"
                                            type="text"
                                            id="agency_name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-6">
                                        <BaseInput
                                            v-model="form.agency_url"
                                            label="Enter Website URL"
                                            type="text"
                                            id="agency_website"
                                            :errors="errors"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.agency_address"
                                            label="Enter Agency Address"
                                            type="text"
                                            id="agency_address"
                                        />
                                    </div>
                                    <div class="col-lg-6">
                                        <BaseInput
                                            v-model="form.agency_contact"
                                            label="Enter Agency Contact Number"
                                            type="text"
                                            id="agency_contact"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12">
                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                            <label class="form-label fs-6 fw-bolder mb-3">Agency Logo</label>
                                            <div class="image-input image-input-outline" style="width: 100%">
                                                <div class="image-input-wrapper h-125px" style="width: 100%">
                                                    <img :src="photo_url" class="img-fluid" style="height: 125px; width: 100%; object-fit: cover;">
                                                </div>
                                                <label
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="change"
                                                    data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Change avatar"
                                                >
                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" @change="onFileChange" multiple enctype="multipart/form-data" />
                                                </label>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="cancel"
                                                    data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Cancel avatar"
                                                >
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="remove"
                                                    @click="removeLogo"
                                                    data-bs-toggle="tooltip"
                                                    title=""
                                                    data-bs-original-title="Remove avatar"
                                                >
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                            </div>
                                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
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
import { onMounted, reactive, ref } from 'vue';
import configRepo from '@/repositories/settings/agency.js';

export default {
    setup() {
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true
        });
        const form = reactive({
            agency_name: '',
            agency_url: '',
            agency_address: '',
            agency_contact: '',
            agency_logo: ''
        });
        const { status, errors, config, getConfig, storeConfig } = configRepo();
        const photo_url = ref('');
        const isSuccess = ref(false);

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('agency_name', form.agency_name ?? '');
            formData.append('agency_website', form.agency_url ?? '');
            formData.append('address', form.agency_address ?? '');
            formData.append('contact_number', form.agency_contact ?? '');
            formData.append('logo', form.agency_logo ?? '');
            formData.append('agency_id', page.authuser.agency_id ?? '');
            formData.append('type', 'agency');

            await storeConfig(formData);
            isSuccess.value = true;
        }

        const onFileChange = (e) => {
            const file = e.target.files[0];
            photo_url.value = URL.createObjectURL(file);
            form.agency_logo = file;
        }

        const removeLogo = () => {
            photo_url.value = config.value.display_logo;
            form.agency_logo = '';
        }

        onMounted( async () => {
            await getConfig();
            form.agency_name = config.value.agency_name;
            form.agency_url = config.value.agency_website;
            form.agency_address = config.value.address;
            form.agency_contact = config.value.contact_number;
            photo_url.value = config.value.display_logo ?? '';
            page.isLoading = false;
        });

        return {
            page,
            form,
            photo_url,
            isSuccess,
            saveChanges,
            onFileChange,
            removeLogo,
            status,
            errors,
            getConfig,
            storeConfig
        }
    },
}
</script>