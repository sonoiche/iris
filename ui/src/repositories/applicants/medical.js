import axios from "axios";
import { ref, inject } from "vue";

export default function medicalRepo() {

    const medicals = ref([]);
    const medical  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getMedicals = async (applicant_id) => {
        let response = await axios.get(`client/medical?applicant_id=${applicant_id}`);
        medicals.value = response.data.data;
    }

    const getMedical = async (id) => {
        let response = await axios.get(`client/medical/${id}`);
        medical.value = response.data.data;
    }

    const storeMedical = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/medical`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateMedical = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/medical/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            medical.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyMedical = async (id) => {
        let response = await axios.delete(`client/medical/${id}`);
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
        medicals,
        medical,
        errors,
        message,
        status,
        getMedicals,
        getMedical,
        storeMedical,
        updateMedical,
        destroyMedical,
        alertmessage
    }

}