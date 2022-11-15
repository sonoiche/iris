<template>
    <div>
        <modal :active="isActive" size="xl">
            <template #header>
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Update Job Description</h1>
                </div>
            </template>
            <template #body>
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <base-editor :message="position.job_description" @save-content="saveContent" />
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
import { reactive, ref, watch } from 'vue';
import positionRepo from '@/repositories/employer/position';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        position_id: {
            type: [Number, String],
            default: []
        },
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    setup(props, {emit}) {
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser'))
        })
        const isSuccess = ref(false);
        const { errors, status, position, getPosition, updateJobDescription } = positionRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('job_description', position.job_description ?? '');
            formData.append('_method', 'PUT');
            formData.append('id', props.position_id);
            await updateJobDescription(formData, props.position_id);
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
            }
        }

        const cancel = () => {
            errors.value = [];
            emit('close-modal');
        }

        const saveContent = (message) => {
            position.job_description = message;
        }

        watch(() => props.position_id, async () => {
            if(props.position_id) {
                await getPosition(props.position_id);
            }
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            position,
            updateJobDescription,
            saveContent
        }
    },
}
</script>