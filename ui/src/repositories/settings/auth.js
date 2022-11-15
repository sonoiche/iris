import axios from "axios";
import { ref, inject } from "vue";

export default function authRepo() {

    const errors = ref([]);
    const status = ref('');
    const user   = ref([]);
    const code   = ref('');

    const twoFAEnable = async (page) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/two-factor-authentication`, {
                user_id: page.authuser.id
            });
            status.value = response.status;
            user.value   = response.data.user;
            code.value   = response.data.code;
            localStorage.setItem('authuser', JSON.stringify(response.data.user));
            localStorage.setItem('auth-qrcode', response.data.qrcode);
            page.qrcode = response.data.qrcode;
            page.isLoading = false;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const twoFADisable = async (page) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/two-factor-authentication/disable`, {
                user_id: page.authuser.id
            });
            status.value = response.status;
            user.value   = response.data.user;
            localStorage.setItem('authuser', JSON.stringify(response.data.user));
            localStorage.removeItem('auth-qrcode');
            page.isTwoFactorEnabled = false;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const twoFAChallenge = async (page, data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/two-factor-authentication/challenge`, {
                user_id: page.authuser.id,
                code: data
            });
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const twoFASMSEnable = async (page) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/two-factor-sms-authentication`, {
                user_id: page.authuser.id
            });
            status.value = response.status;
            user.value   = response.data.user;
            localStorage.setItem('authuser', JSON.stringify(response.data.user));
            page.isLoading = false;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateAuthentication = async (data, page) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/two-factor-sms-authentication`, data);
            status.value = response.status;
            user.value   = response.data.user;
            localStorage.setItem('authuser', JSON.stringify(response.data.user));
            page.isLoading = false;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const getUser = async (page) => {
        let response = await axios.get(`client/users/${page.authuser.id}`);
        user.value = response.data.user;
        code.value = response.data.code;
    }

    return {
        errors,
        status,
        user,
        code,
        twoFAEnable,
        twoFADisable,
        twoFAChallenge,
        twoFASMSEnable,
        getUser,
        updateAuthentication
    }

}