import axios from "axios";
import { ref, inject } from "vue";

export default function reportRepo() {

    const status = ref('');
    const applicants = ref([]);
    const errors = ref('');

    const generateApplicantSource = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/reports/applicant-source`, data);
            status.value = response.status;
            applicants.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    return {
        status,
        applicants,
        generateApplicantSource
    }

}