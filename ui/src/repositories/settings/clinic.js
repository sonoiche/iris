import axios from "axios";
import { ref, inject } from "vue";

export default function clinicRepo() {
    
    const clinics = ref([]);
    const clinic  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getClinics = async (agency_id) => {
        let response = await axios.get(`client/clinics?agency_id=${agency_id}`);
        clinics.value = response.data.data;
    }

    const getClinic = async (id) => {
        let response = await axios.get(`client/clinics/${id}`);
        clinic.value = response.data.data;
    }

    const storeClinic = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/clinics`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            clinic.value = response.data.clinic;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateClinic = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/clinics/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyClinic = async (id) => {
        let response = await axios.delete(`client/clinics/${id}`);
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
        clinics,
        clinic,
        errors,
        message,
        status,
        getClinics,
        getClinic,
        storeClinic,
        updateClinic,
        destroyClinic,
        alertmessage
    }

}