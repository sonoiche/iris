import axios from "axios";
import { ref, inject } from "vue";

export default function licenseRepo() {

    const licenses = ref([]);
    const license  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getLicenses = async (applicant_id) => {
        let response = await axios.get(`client/licenses?applicant_id=${applicant_id}`);
        licenses.value = response.data.data;
    }

    const getLicense = async (id) => {
        let response = await axios.get(`client/licenses/${id}`);
        license.value = response.data.data;
    }

    const storeLicense = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/licenses`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateLicense = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/licenses/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyLicense = async (id) => {
        let response = await axios.delete(`client/licenses/${id}`);
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
        licenses,
        license,
        errors,
        message,
        status,
        getLicenses,
        getLicense,
        storeLicense,
        updateLicense,
        destroyLicense,
        alertmessage
    }

}