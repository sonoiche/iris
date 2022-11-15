import axios from "axios";
import { ref, inject } from "vue";

export default function skillRepo() {

    const skills = ref([]);
    const skill  = ref([]);
    const skill_levels = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getSkills = async (applicant_id) => {
        let response = await axios.get(`client/skills?applicant_id=${applicant_id}`);
        skills.value = response.data.data;
    }

    const getSkill = async (id) => {
        let response = await axios.get(`client/skills/${id}`);
        skill.value = response.data.data;
    }

    const getSkillLevels = async () => {
        let response = await axios.get(`client/skills/levels`);
        skill_levels.value = response.data.data;
    }

    const storeSkill = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/skills`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateSkill = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/skills/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            skill.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroySkill = async (id) => {
        let response = await axios.delete(`client/skills/${id}`);
        alertmessage(response.data.message);
        status.value = response.status;
    }

    const alertmessage = (message) => {
        swal({
            icon: 'success',
            title: 'Great!',
            text: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

    return {
        skill_levels,
        skills,
        skill,
        errors,
        message,
        status,
        getSkills,
        getSkill,
        storeSkill,
        updateSkill,
        destroySkill,
        getSkillLevels,
        alertmessage
    }

}