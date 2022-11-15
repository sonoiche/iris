import axios from "axios";
import { ref, inject } from "vue";

export default function principalRepo() {
    
    const principals = ref([]);
    const principal  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getPrincipals = async (agency_id) => {
        let response = await axios.get(`client/principals?agency_id=${agency_id}`);
        principals.value = response.data.data;
    }

    const getPrincipal = async (id) => {
        let response = await axios.get(`client/principals/${id}`);
        principal.value = response.data.data;
    }

    const getSelectPrincipal = async () => {
        let response = await axios.get(`client/principals/select-option`);
        principals.value = response.data.data;
    }

    const storePrincipal = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/principals`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            principal.value = response.data.principal;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updatePrincipal = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/principals/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyPrincipal = async (id) => {
        let response = await axios.delete(`client/principals/${id}`);
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
        principals,
        principal,
        errors,
        message,
        status,
        getPrincipals,
        getPrincipal,
        getSelectPrincipal,
        storePrincipal,
        updatePrincipal,
        destroyPrincipal,
        alertmessage
    }

}