import axios from "axios";
import { ref, inject } from "vue";

export default function educationRepo() {

    const studies = ref([]);
    const education_levels = ref([]);
    const educations = ref([]);
    const education  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getEducations = async (applicant_id) => {
        let response = await axios.get(`client/educations?applicant_id=${applicant_id}`);
        educations.value = response.data.data;
    }

    const getEducationLevels = async () => {
        let response = await axios.get(`client/educations/levels`);
        education_levels.value = response.data.data;
    }

    const getFieldStudy = async () => {
        let response = await axios.get(`client/educations/studies`);
        studies.value = response.data.data;
    }

    const getEducation = async (id) => {
        let response = await axios.get(`client/educations/${id}`);
        education.value = response.data.data;
    }

    const storeEducation = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/educations`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateEducation = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/educations/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyEducation = async (id) => {
        let response = await axios.delete(`client/educations/${id}`);
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
        studies,
        education_levels,
        educations,
        education,
        errors,
        message,
        status,
        getEducations,
        getEducation,
        storeEducation,
        updateEducation,
        destroyEducation,
        getEducationLevels,
        getFieldStudy,
        alertmessage
    }

}