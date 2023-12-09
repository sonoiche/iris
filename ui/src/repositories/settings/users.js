import axios from "axios";
import { ref, inject } from "vue";

export default function userRepo() {
    
    const users = ref([]);
    const user  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getUsers = async () => {
        let response = await axios.get(`client/users`);
        users.value = response.data.data;
    }

    const getUser = async (id) => {
        let response = await axios.get(`client/users/${id}`);
        user.value = response.data.user;
    }

    const getSelectUser = async () => {
        let response = await axios.get(`client/users/select-option`);
        users.value = response.data.data;
    }

    const storeUser = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/users`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            user.value = response.data.user;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateUser = async (data, id) => {
        errors.value = '';
        let authuser = JSON.parse(localStorage.getItem('authuser'));
        try {
            let response = await axios.post(`client/users/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            if(authuser.id == id) {
                localStorage.setItem('authuser', JSON.stringify(response.data.user));
            }
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateUserEmail = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/users/${id}/email`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateUserPassword = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/users/${id}/password`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateBackup = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/users/${id}/backup`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            localStorage.setItem('authuser', JSON.stringify(response.data.user));
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyUser = async (id) => {
        let response = await axios.delete(`client/users/${id}`);
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
        users,
        user,
        errors,
        message,
        status,
        getUsers,
        getUser,
        storeUser,
        updateUser,
        updateUserEmail,
        updateUserPassword,
        destroyUser,
        getSelectUser,
        updateBackup,
        alertmessage
    }

}