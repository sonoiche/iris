import axios from "axios";
import { ref, inject } from "vue";

export default function emailTemplateRepo() {
    
    const templates = ref([]);
    const template  = ref([]);
    const errors = ref([]);
    const message = ref('');
    const status = ref('');
    const swal = inject('$swal');

    const getEmailTemplates = async () => {
        let response = await axios.get(`client/email-template`);
        templates.value = response.data.data;
    }

    const getEmailTemplate = async (id) => {
        let response = await axios.get(`client/email-template/${id}`);
        template.value = response.data.data;
    }

    const storeEmailTemplate = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/email-template`, data);
            alertmessage(response.data.message);
            status.value = response.status;
            template.value = response.data.template;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const updateEmailTemplate = async (data, id) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/email-template/${id}`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
    }

    const destroyEmailTemplate = async (id) => {
        let response = await axios.delete(`client/email-template/${id}`);
        alertmessage(response.data.message);
        status.value = response.status;
    }

    const sendEmailToApplicant = async (data) => {
        errors.value = '';
        try {
            let response = await axios.post(`client/email-template/send`, data);
            alertmessage(response.data.message);
            status.value = response.status;
        } catch (e) {
            if(e.response.status === 422) {
                errors.value = e.response.data.errors;
                status.value = e.response.status;
            }
        }
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
        templates,
        template,
        errors,
        message,
        status,
        getEmailTemplates,
        getEmailTemplate,
        storeEmailTemplate,
        updateEmailTemplate,
        destroyEmailTemplate,
        sendEmailToApplicant,
        alertmessage
    }

}