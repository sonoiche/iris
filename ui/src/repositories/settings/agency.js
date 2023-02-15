import axios from "axios";
import { ref, inject } from "vue";

export default function configRepo() {
    
    const configs = ref([]);
    const config  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getConfigs = async (agency_id) => {
        let response = await axios.get(`client/configs?agency_id=${agency_id}`);
        configs.value = response.data.data;
    }

    const getConfig = async () => {
        let response = await axios.get(`client/configs/1`);
        config.value = response.data.data;
    }

    const storeConfig = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/configs`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            config.value = response.data.config;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateConfig = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/configs/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            localStorage.setItem('authconfig', JSON.stringify(response.data.config));
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
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
        configs,
        config,
        errors,
        message,
        status,
        getConfigs,
        getConfig,
        storeConfig,
        updateConfig,
        alertmessage
    }

}