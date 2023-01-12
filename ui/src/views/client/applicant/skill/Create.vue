<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add Skill / Strength</h3>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-success btn-sm" @click="backPage">Back</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <div class="d-grid gap-2 col-8 mx-auto">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="skill.name"
                                            label="Skill"
                                            type="text"
                                            id="name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Level of Proficiency"
                                            :options="skill_levels"
                                            :placeholder="`Select Skill Level`"
                                            :defaultValue="{ id: skill.skill_level, name: skill.skill_level_name }"
                                            :is-clear="isClear"
                                            id="skill_level"
                                            @select-value="setSkillLevel"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                        <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="skill.remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-6 mt-3">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="d-flex justify-content-end">
                                            <base-button :success="isSuccess" :btn-text="`Save & Add Another`" @submit-form="saveChanges" /> &nbsp;&nbsp;
                                            <base-button :success="isContinue" :btn-text="`Save & Continue`" @submit-form="saveContinue" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import skillRepo from '@/repositories/applicants/skill';
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            addContinue: false,
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });
        const { status, errors, skill, storeSkill, skill_levels, getSkillLevels } = skillRepo();

        const isSuccess = ref(false);
        const isContinue = ref(false);
        const isClear = ref(false);

        const setSkillLevel = (value) => {
            errors.value.skill_level = '';
            skill.value.skill_level = value.id;
        }

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const saveContinue = () => {
            isContinue.value = false;
            state.addContinue = true;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('name', skill.value.name ?? '');
            formData.append('skill_level', skill.value.skill_level ?? '');
            formData.append('remarks', skill.value.remarks ?? '');
            formData.append('applicant_id', route.params.id);
            formData.append('user_id', state.authuser.id);
            await storeSkill(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                skill.value = [];
                isClear.value = true;
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantSkill');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantSkill');
        }

        onMounted(() => {
            getSkillLevels();
        });

        return {
            state,
            status,
            errors,
            skill,
            storeSkill,
            skill_levels,
            getSkillLevels,
            setSkillLevel,
            isSuccess,
            isContinue,
            isClear,
            saveChanges,
            saveContinue,
            submitForm,
            backPage
        }
    },
}
</script>