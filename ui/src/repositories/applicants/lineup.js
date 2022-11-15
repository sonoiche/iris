import axios from "axios";
import { ref, inject } from "vue";

export default function lineupRepo() {

    const lineups = ref([]);
    const lineup  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getLineups = async (applicant_id) => {
        let response = await axios.get(`client/lineup?applicant_id=${applicant_id}`);
        lineups.value = response.data.data;
    }

    const getLineup = async (id) => {
        let response = await axios.get(`client/lineup/${id}`);
        lineup.value = response.data.data;
    }

    const getLineupByStatus = async (state) => {
        let response = await axios.get(`client/lineup/${state.status_id}?position_id=${state.position_id}`);
        lineups.value = response.data.data;
    }

    const storeLineup = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/lineup`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateLineup = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/lineup/update`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            lineup.value = response.data.data;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyLineup = async (id) => {
        let response = await axios.delete(`client/lineup/${id}`);
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
        lineups,
        lineup,
        errors,
        message,
        status,
        getLineups,
        getLineup,
        getLineupByStatus,
        storeLineup,
        updateLineup,
        destroyLineup,
        alertmessage
    }

}