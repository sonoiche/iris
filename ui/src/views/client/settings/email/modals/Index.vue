<template>
    <div>
        <modal :active="isActive" :size="`xl`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (template.id) ? `Update Email Template` : `Add Email Template` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="template.title"
                                label="Title"
                                type="text"
                                id="title"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <base-editor :message="template.content" @save-content="saveContent" />
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
import emailTemplateRepo from '@/repositories/settings/email';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        template: {
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
            isLoading: true,
            template: ''
        })
        const isSuccess = ref(false);
        const { errors, status, storeEmailTemplate, updateEmailTemplate } = emailTemplateRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('title', props.template.title ?? '');
            formData.append('content', page.template ?? '');
            formData.append('agency_id', page.authuser.agency_id);

            if(props.template.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.template.id);
                await updateEmailTemplate(formData, props.template.id);
            } else {
                await storeEmailTemplate(formData);
            }
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const saveContent = (message) => {
            page.template = message;
        }

        const cancel = () => {
            errors.value = [];
            emit('close-modal');
        }

        return {
            page,
            isSuccess,
            saveChanges,
            saveContent,
            cancel,
            errors,
            status,
            storeEmailTemplate,
            updateEmailTemplate
        }
    },
}
</script>