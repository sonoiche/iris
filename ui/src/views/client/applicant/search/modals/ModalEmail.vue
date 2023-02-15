<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Applicant Email Sending</h1>
                </div>
            </template>
            <template #body>
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect 
                                label="Email Template"
                                :options="templateOptions"
                                :placeholder="`Select Template`"
                                id="template"
                                @select-value="setTemplate"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <base-editor :message="state.content" @save-content="saveContent" />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" :btn-text="`Send Email`" @submit-form="sendEmail" />
            </template>
        </modal>
    </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue';
import emailTemplateRepo from '@/repositories/settings/email';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        isLoading: {
            type: Boolean,
            default: true
        },
        applicant_id: {
            type: String,
            default: ''
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            content: '',
            template_id: 0
        });
        const { getEmailTemplates, templates, template, getEmailTemplate, sendEmailToApplicant, status } = emailTemplateRepo();
        const templateOptions = ref([]);
        const isSuccess = ref(false);

        const sendEmail = async () => {
            isSuccess.value = true;
            let formData = new FormData();
            formData.append('applicant_id', props.applicant_id);
            formData.append('content', state.content ?? '');
            formData.append('template_id', state.template_id ?? '');
            await sendEmailToApplicant(formData);

            if(status.value == 200) {
                isSuccess.value = false;
                emit('close-modal');
            }
        }

        const setTemplate = async (evt) => {
            await getEmailTemplate(evt.id);
            state.content = template.value.content;
            state.template_id = evt.id;
        }

        const saveContent = (evt) => {
            state.content = evt;
        }

        const cancel = () => {
            state.content = '';
            state.template_id = 0;
            emit('close-modal');
        }

        onMounted( async () => {
            await getEmailTemplates();
            templates.value.forEach(item => {
                templateOptions.value.push({
                    id: item.id,
                    name: item.title
                });
            });
        });

        return {
            state,
            templateOptions,
            sendEmail,
            setTemplate,
            getEmailTemplates,
            templates,
            template,
            getEmailTemplate,
            sendEmailToApplicant,
            saveContent,
            cancel,
            status,
            isSuccess
        }
    }
}
</script>