import axios from "axios";
import { ref } from "vue";

export default function nationalityRepo() {
    
    const nationalities = ref([]);
    const nationality  = ref([]);

    const getNationalities = async () => {
        let response = await axios.get(`client/nationality`);
        nationalities.value = response.data.data;
    }

    const getNationality = async (id) => {
        let response = await axios.get(`client/nationality/${id}`);
        nationality.value = response.data.data;
    }

    return {
        nationalities,
        nationality,
        getNationalities,
        getNationality
    }

}