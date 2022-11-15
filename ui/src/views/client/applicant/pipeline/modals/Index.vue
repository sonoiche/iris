<template>
    <div>
        <modal :active="isActive" :size="`xl`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">Applicant Lineup</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect
                                label="Applicant Name"
                                :placeholder="`Enter Applicant Name`"
                                :id="`applicant-name`"
                                :options="applicantOptions"
                                :multiple="true"
                                @select-value="setApplicants"
                                @remove-value="removeApplicants"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" :btnDisabled="page.disabled" @submit-form="saveChanges" />
            </template>
        </modal>
    </div>
</template>

<script>
import { onBeforeUpdate, reactive, ref, watchEffect } from 'vue';
import lineupRepo from '@/repositories/applicants/lineup';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        lineup: {
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
            disabled: true
        })
        const isSuccess = ref(false);
        const applicants = ref([]);
        const { errors, status, storeLineup } = lineupRepo();
        const { applicantOptions, getOptionApplicants } = applicantRepo();

        const setApplicants = (value) => {
            applicants.value.push(value.id);
        }

        const removeApplicants = (value) => {
            applicants.value.splice(applicants.value.indexOf(value), 1);
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('position_id', props.lineup.position_id ?? '');
            formData.append('status_id', props.lineup.status_id ?? '');
            formData.append('user_id', page.authuser.id);
            formData.append('applicants', applicants.value ?? '');

            await storeLineup(formData);

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

        onBeforeUpdate( async () => {
            await getOptionApplicants();
        });

        watchEffect(() => {
            page.disabled = applicants.value.length == 0;
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            storeLineup,
            setApplicants,
            removeApplicants,
            applicantOptions,
            getOptionApplicants
        }
    },
}
</script>