import axios from "axios";
import { ref, inject } from "vue";

export default function documentTypeRepo() {
    
    const documents = ref([]);
    const document  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getDocuments = async (agency_id) => {
        let response = await axios.get(`client/document-types?agency_id=${agency_id}`);
        documents.value = response.data.data;
    }

    const getDocument = async (id) => {
        let response = await axios.get(`client/document-types/${id}`);
        document.value = response.data.data;
    }

    const storeDocument = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/document-types`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            document.value = response.data.document;
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
            let response = await axios.post(`client/document-types/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyDocument = async (id) => {
        let response = await axios.delete(`client/document-types/${id}`);
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