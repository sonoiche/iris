import axios from "axios";
import { ref, inject } from "vue";

export default function joborderRepo() {

    const joborders = ref([]);
    const joborder  = ref([]);
    const filters   = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getJobOrders = async () => {
        let response = await axios.get(`client/joborders`);
        joborders.value = response.data.data;
    }

    const getJobOrderPositions = async () => {
        let response = await axios.get(`client/joborders/positions`);
        joborders.value = response.data.data;
    }

    const getJobOrder = async (id) => {
        let response = await axios.get(`client/joborders/${id}`);
        joborder.value = response.data.data;
    }

    const getFilters = async () => {
        let data = JSON.parse(localStorage.getItem('filter')) ?? [];
        let response = await axios.post(`client/joborders/filter`, data);
        filters.value = response.data;
    }

    const storeJobOrder = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/joborders`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            joborder.value = response.data.joborder;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateJobOrder = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/joborders/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateJobOrderUsers = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/joborders/users/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyJobOrder = async (id) => {
        let response = await axios.delete(`client/joborders/${id}`);
        alertmessage(response.data.message);
        status.value = response.status;
    }

    const getJobOrdersByPrincipal = async (principal_id) => {
        let response = await axios.get(`client/joborders?principal_id=${principal_id}`);
        joborders.value = response.data.data;
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
        joborders,
        joborder,
        errors,
        message,
        status,
        getJobOrders,
        getJobOrderPositions,
        getJobOrder,
        storeJobOrder,
        updateJobOrder,
        updateJobOrderUsers,
        destroyJobOrder,
        getFilters,
        filters,
        getJobOrdersByPrincipal,
        alertmessage
    }

}