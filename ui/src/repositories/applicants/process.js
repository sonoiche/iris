import axios from "axios";
import { ref, inject } from "vue";

export default function processRepo() {

    const processings = ref([]);
    const processing  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getProcessings = async (applicant_id) => {
        let response = await axios.get(`client/processing?applicant_id=${applicant_id}`);
        processings.value = response.data.data;
    }

    const getProcessing = async (applicant_id) => {
        let response = await axios.get(`client/processing/${applicant_id}`);
        processing.value = response.data.data;
    }

    const storeProcess = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/processing`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateProcess = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/processing/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            processing.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyProcess = async (id) => {
        let response = await axios.delete(`client/processing/${id}`);
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
        processings,
        processing,
        errors,
        message,
        status,
        getProcessings,
        getProcessing,
        storeProcess,
        updateProcess,
        destroyProcess,
        alertmessage
    }

}