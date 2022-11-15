import axios from "axios";
import { ref, inject } from "vue";

export default function referenceRepo() {

    const references = ref([]);
    const reference  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getReferences = async (applicant_id) => {
        let response = await axios.get(`client/reference?applicant_id=${applicant_id}`);
        references.value = response.data.data;
    }

    const getReference = async (id) => {
        let response = await axios.get(`client/reference/${id}`);
        reference.value = response.data.data;
    }

    const storeReference = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/reference`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateReference = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/reference/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            reference.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyReference = async (id) => {
        let response = await axios.delete(`client/reference/${id}`);
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
        references,
        reference,
        errors,
        message,
        status,
        getReferences,
        getReference,
        storeReference,
        updateReference,
        destroyReference,
        alertmessage
    }

}