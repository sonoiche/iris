import axios from "axios";
import { ref, inject } from "vue";

export default function industryRepo() {
    
    const industries = ref([]);
    const industry  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getIndustries = async () => {
        let response = await axios.get(`client/industry`);
        industries.value = response.data.data;
    }

    const getSelectIndustry = async () => {
        let response = await axios.get(`client/industry/select-option`);
        industries.value = response.data.data;
    }

    const getIndustry = async (id) => {
        let response = await axios.get(`client/industry/${id}`);
        industry.value = response.data.data;
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
        industries,
        industry,
        errors,
        message,
        status,
        getIndustries,
        getSelectIndustry,
        getIndustry,
        alertmessage
    }

}