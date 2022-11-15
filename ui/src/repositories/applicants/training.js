import axios from "axios";
import { ref, inject } from "vue";

export default function trainingRepo() {

    const trainings = ref([]);
    const training  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getTrainings = async (applicant_id) => {
        let response = await axios.get(`client/trainings?applicant_id=${applicant_id}`);
        trainings.value = response.data.data;
    }

    const getTraining = async (id) => {
        let response = await axios.get(`client/trainings/${id}`);
        training.value = response.data.data;
    }

    const storeTraining = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/trainings`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateTraining = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/trainings/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            training.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyTraining = async (id) => {
        let response = await axios.delete(`client/trainings/${id}`);
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
        trainings,
        training,
        errors,
        message,
        status,
        getTrainings,
        getTraining,
        storeTraining,
        updateTraining,
        destroyTraining,
        alertmessage
    }

}