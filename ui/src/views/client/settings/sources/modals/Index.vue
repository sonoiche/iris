<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">{{ (source.id) ? `Update Applicant Source` : `Add Applicant Source` }}</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseInput 
                                v-model="source.name"
                                label="Source Name"
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
import sourceRepo from '@/repositories/settings/source';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        source: {
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
        const { errors, status, storeSource, updateSource } = sourceRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', props.source.name ?? '');
            formData.append('agency_id', page.authuser.agency_id);

            if(props.source.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.source.id);
                await updateSource(formData, props.source.id);
            } else {
                await storeSource(formData);
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
            storeSource,
            updateSource
        }
    },
}
</script>