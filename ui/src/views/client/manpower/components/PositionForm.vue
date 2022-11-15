<template>
    <div>
        <loading v-if="isLoading" />
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework" v-else>
            <div class="row mb-6">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="position.position_title"
                        label="Position Title"
                        type="text"
                        id="position_title"
                        :errors="errors"
                        is-required
                    />
                </div>
            </div>
            <div class="row mb-6" v-if="position.any_gender !== true && position.any_gender !== 1">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="position.number_of_male"
                        label="Number of Male"
                        type="number"
                        id="number_of_male"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="position.number_of_female"
                        label="Number of Female"
                        type="number"
                        id="number_of_female"
                    />
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-12">
                    <label for="any_gender" class="form-label fs-6 fw-bolder mb-3">Any Gender</label>
                    <div class="d-flex">
                        <div class="form-check form-check-solid fv-row fv-plugins-icon-container gender-checkbox">
                            <input class="form-check-input" type="checkbox" v-model="isChecked" id="gender" />
                        </div>
                        <div class="pl-20 w-full" v-if="position.any_gender === true || position.any_gender === 1">
                            <BaseInput 
                                v-model="position.total_number"
                                type="number"
                                id="total_number"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="position.propose_salary"
                        label="Propose Salary"
                        type="number"
                        id="propose_salary"
                        :errors="errors"
                    />
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <BaseInput 
                        v-model="position.propose_food_allowance"
                        label="Propose Food Allowance"
                        type="number"
                        id="propose_food_allowance"
                        :errors="errors"
                    />
                </div>
            </div>
            <div class="row mb-6 mt-3">
                <div class="col-lg-12 mb-4 mb-lg-0">
                    <div class="d-flex" :class="{ 'justify-content-between' : position.id, 'justify-content-end' : !position.id }">
                        <button v-if="position.id" class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button>
                        <div class="d-flex">
                            <button v-if="position.id" class="btn btn-primary mr-10" @click="addJobDescription">{{ position.job_description ? 'Update Job Description' : 'Add Job Description' }}</button>
                            <base-button :success="isSuccess" @submit-form="saveChanges" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ModalJobDescription :is-active="modalActive" :position_id="position_id" @close-modal="closeModalJobDescription" />
    </div>
</template>

<script>
import { onMounted, ref, watchEffect, watch } from 'vue';
import positionRepo from '@/repositories/employer/position';
import ModalJobDescription from '@/views/client/manpower/modals/JobDescription.vue';

export default {
    props: {
        job_order_id: {
            type: [String, Number],
            default: ''
        },
        position_id: {
            type: [String, Number],
            default: ''
        }
    },
    setup(props, {emit}) {
        const { status, errors, position, getPosition, storePosition, updatePosition } = positionRepo();
        const isSuccess = ref(false);
        const isLoading = ref(true);
        const isChecked = ref(false);
        const modalActive = ref(false);

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('job_order_id', props.job_order_id ?? '');
            formData.append('position_title', position.value.position_title ?? '');
            formData.append('number_of_male', position.value.number_of_male ?? '');
            formData.append('number_of_female', position.value.number_of_female ?? '');
            formData.append('any_gender', isChecked.value ?? '');
            formData.append('total_number', position.value.total_number ?? '');
            formData.append('propose_salary', position.value.propose_salary ?? '');
            formData.append('propose_food_allowance', position.value.propose_food_allowance ?? '');
            formData.append('job_description', position.value.job_description ?? '');

            if(position.value.id) {
                formData.append('_method', 'PUT');
                await updatePosition(formData, position.value.id);
            } else {
                await storePosition(formData);
            }

            isSuccess.value = true;
            if(status.value == 200) {
                position.value = [];
                isChecked.value = false;
                emit('submit-status', status.value);
            }
        }

        const addJobDescription = () => {
            modalActive.value = true;
        }

        const closeModalJobDescription = () => {
            modalActive.value = false;
        }

        const cancel = () => {
            position.value = [];
            isChecked.value = false;
            emit('submit-cancel');
        }

        onMounted(() => {
            isLoading.value = false;
        });

        watchEffect(() => {
            if(props.position_id !== '' && position.value.any_gender === true || position.value.any_gender === 1) {
                isChecked.value = true;
            }

            if(props.position_id !== '' && position.value.any_gender === false || position.value.any_gender === 0) {
                isChecked.value = false;
            }
        });

        watch(() => props.position_id, async () => {
            if(props.position_id) {
                await getPosition(props.position_id);
            }
        });

        watch(() => isChecked.value, () => {
            position.value.any_gender = isChecked.value;
        })

        return {
            isSuccess,
            isLoading,
            isChecked,
            status,
            errors,
            position,
            getPosition,
            storePosition,
            updatePosition,
            saveChanges,
            addJobDescription,
            modalActive,
            closeModalJobDescription,
            cancel
        }
    },
    components: {
        ModalJobDescription
    }
}
</script>

<style scoped>
.w-full {
    width: 100%;
}
.pl-20 {
    padding-left: 20px;
}
.gender-checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}
.mr-10 {
    margin-right: 10px;
}
</style>