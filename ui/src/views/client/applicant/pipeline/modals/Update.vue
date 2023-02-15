<template>
    <div>
        <modal :active="isActive" :size="`xl`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">Update Applicant Lineup</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect
                                label="Status"
                                :placeholder="`Enter Status`"
                                :id="`status_id`"
                                :options="statusOptions"
                                :errors="errors"
                                is-required
                                @select-value="setStatus"
                            />
                        </div>
                    </div>
                    <div class="row mb-1" v-if="isForInterview">
                        <div class="col-lg-6">
                            <BaseDatePicker
                                v-model="page.interview_date"
                                :defaultValue="page.interview_date"
                                label="Interview Date"
                                id="interview_date"
                                :errors="errors"
                                is-required
                            />
                        </div>
                        <div class="col-lg-6">
                            <BaseInput
                                v-model="page.time"
                                label="Interview Time"
                                type="time"
                                id="time"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1" v-if="isForInterview">
                        <div class="col-lg-12">
                            <BaseInput
                                v-model="page.venue"
                                label="Interview Venue"
                                type="text"
                                id="venue"
                                :errors="errors"
                                is-required
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                            <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="page.remarks"></textarea>
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
import { computed, onBeforeUpdate, reactive, ref } from 'vue';
import lineupRepo from '@/repositories/applicants/lineup';
import statusRepo from '@/repositories/settings/status';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        state: {
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
            disabled: true,
            remarks: '',
            status_id: '',
            interview_date: '',
            time: '',
            venue: ''
        })
        const isSuccess = ref(false);
        const isForInterview = ref(false);
        const { errors, status, updateLineup } = lineupRepo();
        const { results, getStatuses } = statusRepo();

        const setStatus = (value) => {
            page.status_id = value.id;
            if(value.id == 3) {
                isForInterview.value = true;
            } else {
                isForInterview.value = false;
            }
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('position_id', props.state.lineup.position_id ?? '');
            formData.append('status_id', page.status_id ?? '');
            formData.append('remarks', page.remarks ?? '');
            formData.append('interview_date', page.interview_date ? new Date(page.interview_date).toISOString() : '');
            formData.append('interview_time', page.time ?? '');
            formData.append('venue', page.venue ?? '');
            formData.append('applicants', props.state.applicant_ids ?? '');
            formData.append('user_id', page.authuser.id);

            await updateLineup(formData);

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

        const statusOptions = computed(() => {
            const arr_status = [];
            results.value.forEach(item => {
                arr_status.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_status;
        });

        onBeforeUpdate( async () => {
            await getStatuses();
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            updateLineup,
            setStatus,
            results,
            getStatuses,
            statusOptions,
            isForInterview
        }
    },
}
</script>