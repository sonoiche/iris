import axios from "axios";
import { ref, inject } from "vue";

export default function positionRepo() {
    
    const positions = ref([]);
    const position  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getPositions = async () => {
        let response = await axios.get(`client/positions`);
        positions.value = response.data.data;
    }

    const getPosition = async (id) => {
        let response = await axios.get(`client/positions/${id}`);
        position.value = response.data.data;
    }

    const storePosition = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/positions`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            position.value = response.data.position;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updatePosition = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/positions/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateJobDescription = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/positions/update-jobdescription/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyPosition = async (id) => {
        let response = await axios.delete(`client/positions/${id}`);
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
        positions,
        position,
        errors,
        message,
        status,
        getPositions,
        getPosition,
        storePosition,
        updatePosition,
        updateJobDescription,
        destroyPosition,
        alertmessage
    }

}