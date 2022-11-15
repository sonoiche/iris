import axios from "axios";
import { ref, inject } from "vue";

export default function statusRepo() {

    const results = ref([]);
    const result  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getStatuses = async () => {
        let response = await axios.get(`client/status`);
        results.value = response.data.data;
    }

    const getStatus = async (id) => {
        let response = await axios.get(`client/status/${id}`);
        result.value = response.data.data;
    }

    const storeStatus = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/status`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            result.value = response.data.result;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateStatus = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/status/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyStatus = async (id) => {
        let response = await axios.delete(`client/status/${id}`);
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
        results,
        result,
        errors,
        message,
        status,
        getStatuses,
        getStatus,
        storeStatus,
        updateStatus,
        destroyStatus,
        alertmessage
    }

}