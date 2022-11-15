import axios from "axios";
import { ref, inject } from "vue";

export default function sourceRepo() {
    
    const sources = ref([]);
    const source  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getSources = async () => {
        let response = await axios.get(`client/sources`);
        sources.value = response.data.data;
    }

    const getSource = async (id) => {
        let response = await axios.get(`client/sources/${id}`);
        source.value = response.data.data;
    }

    const getSourceOptions = async () => {
        let response = await axios.get(`client/sources/options`);
        sources.value = response.data.data;
    }

    const storeSource = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/sources`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            source.value = response.data.source;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateSource = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/sources/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroySource = async (id) => {
        let response = await axios.delete(`client/sources/${id}`);
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
        sources,
        source,
        errors,
        message,
        status,
        getSources,
        getSource,
        getSourceOptions,
        storeSource,
        updateSource,
        destroySource,
        alertmessage
    }

}