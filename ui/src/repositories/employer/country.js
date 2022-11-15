import axios from "axios";
import { ref, inject } from "vue";

export default function countryRepo() {
    
    const countries = ref([]);
    const country  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getCountries = async () => {
        let response = await axios.get(`client/country`);
        countries.value = response.data.data;
    }

    const getSelectCountry = async () => {
        let response = await axios.get(`client/country/select-option`);
        countries.value = response.data.data;
    }

    const getCountry = async (id) => {
        let response = await axios.get(`client/country/${id}`);
        country.value = response.data.data;
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
        countries,
        country,
        errors,
        message,
        status,
        getCountries,
        getSelectCountry,
        getCountry,
        alertmessage
    }

}