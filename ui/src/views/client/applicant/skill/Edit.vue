<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Update Skill / Strength</h3>
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
                                            <button class="btn btn-outline-danger btn-sm" @click="backPage">Cancel</button> &nbsp;&nbsp;
                                            <base-button :success="isSuccess" @submit-form="saveChanges" />
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

export default {
    props: {
        updateId: {
            type: [Number, String],
            default: ''
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            addContinue: false
        });
        const { status, errors, skill, updateSkill, skill_levels, getSkillLevels, getSkill } = skillRepo();

        const isSuccess = ref(false);

        const setSkillLevel = (value) => {
            errors.value.skill_level = '';
            skill.value.skill_level = value.id;
            skill.value.skill_level_name = value.name;
        }

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('name', skill.value.name ?? '');
            formData.append('skill_level', skill.value.skill_level ?? '');
            formData.append('remarks', skill.value.remarks ?? '');
            formData.append('id', skill.value.id ?? '');
            formData.append('_method', 'PUT');
            await updateSkill(formData, props.updateId);
            isSuccess.value = true;
        }

        const backPage = () => {
            emit('add-data', 'ApplicantSkill');
        }

        onMounted( async () => {
            await getSkill(props.updateId);
            getSkillLevels();
        });

        return {
            state,
            status,
            errors,
            skill,
            updateSkill,
            skill_levels,
            getSkillLevels,
            getSkill,
            setSkillLevel,
            isSuccess,
            saveChanges,
            submitForm,
            backPage
        }
    },
}
</script>