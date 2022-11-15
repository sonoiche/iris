import axios from "axios";
import { ref, inject } from "vue";

export default function principalContactRepo() {
    
    const contacts = ref([]);
    const contact  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getPrincipalContacts = async () => {
        let response = await axios.get(`client/principal-contacts`);
        principals.value = response.data.data;
    }

    const getPrincipalContact = async (id) => {
        let response = await axios.get(`client/principal-contacts/${id}`);
        contact.value = response.data.data;
    }

    const storePrincipalContact = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/principal-contacts`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            contact.value = response.data.principal;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updatePrincipalContact = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/principal-contacts/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyPrincipalContact = async (id) => {
        let response = await axios.delete(`client/principal-contacts/${id}`);
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
        contacts,
        contact,
        errors,
        message,
        status,
        getPrincipalContacts,
        getPrincipalContact,
        storePrincipalContact,
        updatePrincipalContact,
        destroyPrincipalContact,
        alertmessage
    }

}