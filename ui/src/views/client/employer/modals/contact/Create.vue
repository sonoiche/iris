<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <loading v-if="page.isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (contact.id) ? `Update Contact` : `Add Contact` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="page.isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="contact.name"
                                label="Name"
                                type="text"
                                id="name"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="contact.position"
                                label="Position"
                                type="text"
                                id="position"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="contact.email"
                                label="Email"
                                type="email"
                                id="email"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="contact.mobile_number"
                                label="Mobile Number"
                                type="text"
                                id="mobile_number"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="contact.skype_username"
                                label="Skype Username"
                                type="text"
                                id="skype_username"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <label class="form-label fs-6 fw-bolder mb-3">Notes</label>
                            <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="contact.remarks"></textarea>
                        </div>
                    </div>
                    <div class="row mb-1 mt-5">
                        <div class="col-lg-12">
                            <div class="form-check form-check-solid fv-row fv-plugins-icon-container">
                                <input class="form-check-input" type="checkbox" v-model="contact.main_contact" value="1" id="main_contact" />
                                <label class="form-check-label fw-bold ps-2 fs-6" for="main_contact">Default Contact Person</label>
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
import { reactive, ref, watchEffect } from 'vue';
import principalContactRepo from '@/repositories/employer/contact';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        contact_id: {
            type: [Number, String],
            default: ''
        },
        principal_id: {
            type: [Number, String],
            default: ''
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
        const { errors, status, contact, getPrincipalContact, storePrincipalContact, updatePrincipalContact } = principalContactRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', contact.value.name ?? '');
            formData.append('position', contact.value.position ?? '');
            formData.append('email', contact.value.email ?? '');
            formData.append('mobile_number', contact.value.mobile_number ?? '');
            formData.append('skype_username', contact.value.skype_username ?? '');
            formData.append('remarks', contact.value.remarks ?? '');
            formData.append('main_contact', contact.value.main_contact ?? '');
            formData.append('principal_id', props.principal_id);

            if(props.contact_id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.contact_id);
                await updatePrincipalContact(formData, props.contact_id);
            } else {
                await storePrincipalContact(formData);
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

        watchEffect( async () => {
            if(props.contact_id) {
                await getPrincipalContact(props.contact_id);
            }
            page.isLoading = false;
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            contact,
            getPrincipalContact,
            storePrincipalContact,
            updatePrincipalContact
        }
    },
}
</script>