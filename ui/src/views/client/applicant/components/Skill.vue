<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">Skill</th>
                    <th class="fw-bolder">Level of Proficiency</th>
                    <th class="fw-bolder">Remarks</th>
                </tr>
            </thead>
            <tbody v-if="skills.length">
                <tr v-for="(skill, index) in skills" :key="skill">
                    <td>{{ index+1 }}</td>
                    <td>{{ skill.name }}</td>
                    <td>{{ skill.skill_level_name }}</td>
                    <td>{{ skill.remarks }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">No records found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import skillRepo from '@/repositories/applicants/skill';

export default {
    props: {
        applicant_id: {
            type: [Number, String],
            default: 0
        }
    },
    setup(props) {
        const state = reactive({
            isLoading: true
        });
        const { skills, getSkills } = skillRepo();

        onMounted( async () => {
            await getSkills(props.applicant_id);
            state.isLoading = false;
        });

        return {
            state,
            skills,
            getSkills
        }
    },
}
</script>