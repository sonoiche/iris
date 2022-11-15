import axios from "axios";
import { ref, inject } from "vue";

export default function interviewRepo() {

    const interviews = ref([]);
    const interview  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getInterviews = async (applicant_id) => {
        let response = await axios.get(`client/interview?applicant_id=${applicant_id}`);
        interviews.value = response.data.data;
    }

    const getInterview = async (id) => {
        let response = await axios.get(`client/interview/${id}`);
        interview.value = response.data.data;
    }

    const storeInterview = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/interview`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateInterview = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/interview/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            interview.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyInterview = async (id) => {
        let response = await axios.delete(`client/interview/${id}`);
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
        interviews,
        interview,
        errors,
        message,
        status,
        getInterviews,
        getInterview,
        storeInterview,
        updateInterview,
        destroyInterview,
        alertmessage
    }

}