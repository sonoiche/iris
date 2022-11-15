import axios from "axios";
import { ref, inject } from "vue";

export default function employmentRepo() {

    const employments = ref([]);
    const employment  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getEmployments = async (applicant_id) => {
        let response = await axios.get(`client/employments?applicant_id=${applicant_id}`);
        employments.value = response.data.data;
    }

    const getEmployment = async (id) => {
        let response = await axios.get(`client/employments/${id}`);
        employment.value = response.data.data;
    }

    const storeEmployment = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/employments`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateEmployment = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/employments/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyEmployment = async (id) => {
        let response = await axios.delete(`client/employments/${id}`);
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
        employments,
        employment,
        errors,
        message,
        status,
        getEmployments,
        getEmployment,
        storeEmployment,
        updateEmployment,
        destroyEmployment,
        alertmessage
    }

}