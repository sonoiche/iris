import axios from "axios";
import { ref, inject } from "vue";

export default function roleRepo() {
    
    const roles = ref([]);
    const role  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');
    const permissions = ref([]);

    const getPermissions = async (role_id = '') => {
        if(role_id) {
            let response = await axios.get(`client/roles/permissions?role_id=${role_id}`);
            permissions.value = response.data.data;
        } else {
            let response = await axios.get(`client/roles/permissions`);
            permissions.value = response.data.data;
        }
    }

    const getRoles = async (agency_id) => {
        let response = await axios.get(`client/roles?agency_id=${agency_id}`);
        roles.value = response.data.data;
    }

    const getRole = async (id) => {
        let response = await axios.get(`client/roles/${id}`);
        role.value = response.data.data;
    }

    const storeRole = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/roles`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            role.value = response.data.role;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateRole = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/roles/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateAllPermission = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/roles/update-permissions`, data);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updatePermission = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/roles/update-permission`, data);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyRole = async (id) => {
        let response = await axios.delete(`client/roles/${id}`);
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
        roles,
        role,
        errors,
        message,
        status,
        getRoles,
        getRole,
        storeRole,
        updateRole,
        destroyRole,
        getPermissions,
        updatePermission,
        updateAllPermission,
        permissions,
        alertmessage
    }

}