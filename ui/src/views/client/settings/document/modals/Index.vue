<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (document.id) ? `Update Document Type` : `Add Document Type` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="document.name"
                                label="Document Type"
                                type="text"
                                id="name"
                                :errors="errors"
                                is-required
                            />
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
import documentTypeRepo from '@/repositories/settings/document_type';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        document: {
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
        const { errors, status, storeDocument, updateDocument } = documentTypeRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', props.document.name ?? '');
            formData.append('agency_id', page.authuser.agency_id);

            if(props.document.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.document.id);
                await updateDocument(formData, props.document.id);
            } else {
                await storeDocument(formData);
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
            storeDocument,
            updateDocument
        }
    },
}
</script>