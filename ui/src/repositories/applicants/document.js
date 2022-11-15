import axios from "axios";
import { ref, inject } from "vue";

export default function documentRepo() {

    const documents = ref([]);
    const document  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getDocuments = async (applicant_id) => {
        let response = await axios.get(`client/document?applicant_id=${applicant_id}`);
        documents.value = response.data.data;
    }

    const getDocument = async (id) => {
        let response = await axios.get(`client/document/${id}`);
        document.value = response.data.data;
    }

    const storeDocument = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/document`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateDocument = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/document/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            document.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyDocument = async (id) => {
        let response = await axios.delete(`client/document/${id}`);
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
        documents,
        document,
        errors,
        message,
        status,
        getDocuments,
        getDocument,
        storeDocument,
        updateDocument,
        destroyDocument,
        alertmessage
    }

}