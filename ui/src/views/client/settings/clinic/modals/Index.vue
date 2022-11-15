<template>
    <div>
        <modal :active="isActive" :size="`xl`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (clinic.id) ? `Update Clinic` : `Add New Clinic` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-12">
                            <BaseInput 
                                v-model="clinic.name"
                                label="Clinic Name"
                                type="text"
                                id="name"
                                :errors="errors"
                                is-required
                            />
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <BaseInput 
                                v-model="clinic.address"
                                label="Clinic Address"
                                type="text"
                                id="address"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-12">
                            <BaseInput 
                                v-model="clinic.contact_number"
                                label="Contact Number"
                                type="text"
                                id="contact_number"
                            />
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <BaseInput 
                                v-model="clinic.contact_person"
                                label="Contact Person"
                                type="text"
                                id="contact_person"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <div class="fv-row mb-0 fv-plugins-icon-container mb-3">
                                <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="clinic.remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" @submit-form="saveChanges" />
            </template>
        </modal>
    </div>
</template>

<script>
import { reactive, ref } from 'vue';
import clinicRepo from '@/repositories/settings/clinic';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        clinic: {
            type: Object,
            default: {}
        },
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    setup(props, {emit}) {
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true
        })
        const isSuccess = ref(false);
        const { errors, status, storeClinic, updateClinic } = clinicRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', props.clinic.name ?? '');
            formData.append('address', props.clinic.address ?? '');
            formData.append('contact_number', props.clinic.contact_number ?? '');
            formData.append('contact_person', props.clinic.contact_person ?? '');
            formData.append('remarks', props.clinic.remarks ?? '');
            formData.append('agency_id', page.authuser.agency_id);

            if(props.clinic.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.clinic.id);
                await updateClinic(formData, props.clinic.id);
            } else {
                await storeClinic(formData);
            }
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const cancel = () => {
            errors.value = [];
            emit('close-modal');
        }

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            storeClinic,
            updateClinic
        }
    },
}
</script>