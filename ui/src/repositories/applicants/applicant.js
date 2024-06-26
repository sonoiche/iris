import axios from "axios";
import { ref, inject } from "vue";

export default function applicantRepo() {

    const applicants = ref([]);
    const applicant  = ref([]);
    const applicant_number = ref('');
    const applicantOptions = ref([]);
    const resumeData = ref([]);
    const resumefile = ref('');
    const resume_id = ref('');
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getApplicants = async () => {
        let response = await axios.get(`client/applicants`);
        applicants.value = response.data.data;
    }

    const getApplicant = async (id) => {
        let response = await axios.get(`client/applicants/${id}`);
        applicant.value = response.data.data;
    }

    const getTrashedApplicants = async () => {
        let response = await axios.get(`client/applicants/trashed`);
        applicants.value = response.data.data;
    }

    const getOptionApplicants = async () => {
        let response = await axios.get(`client/applicants/options`);
        applicantOptions.value = response.data.data;
    }

    const storeApplicant = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            applicant.value = response.data.applicant;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateApplicant = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const encodeApplicant = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants/encode`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            applicant.value = response.data.applicant;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const uploadResumeParser = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants/resume-parser`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            resumefile.value = response.data.resumelink;
            resume_id.value  = response.data.resume_id;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const getResumeParser = async (state) => {
        let response = await axios.get(`client/applicants/get-resume?user_id=${state.authuser.id}`);
        resumeData.value = response.data.data;
        resume_id.value  = response.data.resume_id;
    }

    const deleteResumeParser = async (state) => {
        let response = await axios.delete(`client/applicants/delete-resume?user_id=${state.authuser.id}`);
        status.value = response.status;
    }

    const storeApplicantFromResume = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants/store-resume`, data);
            alertmessage(response.data.message);
            applicant_number.value = response.data.applicant_number;
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const uploadApplicantPhoto = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/applicants/upload-photo`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            applicant.value = response.data.applicant;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyApplicant = async (id) => {
        let response = await axios.delete(`client/applicants/${id}`);
        alertmessage(response.data.message);
        status.value = response.status;
    }
    
    const returnApplicant = async (id) => {
        let response = await axios.get(`client/applicants/${id}/return`);
        alertmessage(response.data.message);
        status.value = response.status;
    }
    
    const permanentDestroyApplicant = async (id) => {
        let response = await axios.delete(`client/applicants/${id}/permanent`);
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
        applicants,
        applicant,
        resumefile,
        resume_id,
        errors,
        message,
        status,
        getApplicants,
        getApplicant,
        storeApplicant,
        updateApplicant,
        encodeApplicant,
        uploadResumeParser,
        destroyApplicant,
        applicantOptions,
        getOptionApplicants,
        getResumeParser,
        deleteResumeParser,
        resumeData,
        applicant_number,
        getTrashedApplicants,
        permanentDestroyApplicant,
        returnApplicant,
        storeApplicantFromResume,
        uploadApplicantPhoto,
        alertmessage
    }

}