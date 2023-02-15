<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Choose An Authentication Method</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="page.isLoading" />
                <div v-else>
                    <div v-if="!onSelected && !page.isTwoFactorEnabled">
                        <p class="text-muted fs-5 fw-bold mb-10">In addition to your username and password, you’ll have to enter a code (delivered via app or SMS) to log into your account.</p>
                        <div class="pb-10">
                            <input type="radio" class="btn-check" v-model="authOption" value="apps" checked="checked" id="2fa_option_1" />
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="2fa_option_1">
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z" fill="currentColor" />
                                        <path d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-3">Authenticator Apps</span>
                                    <span class="text-muted fw-bold fs-6">Get codes from an app like Google Authenticator, Microsoft Authenticator, Authy or 1Password.</span>
                                </span>
                            </label>
                            <input type="radio" class="btn-check" v-model="authOption" value="sms" id="2fa_option_2" />
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="2fa_option_2">
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor" />
                                        <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-3">SMS</span>
                                    <span class="text-muted fw-bold fs-6">We will send a code via SMS if you need to use your backup login method.</span>
                                </span>
                            </label>
                        </div>
                        <button class="btn btn-primary w-100" @click="selectOption">Continue</button>
                        <button @click="cancel" class="btn btn-light me-3 w-100" style="margin-top: 15px">Cancel</button>
                    </div>

                    <div v-else>
                        <div v-if="authOption == 'apps'">
                            <h3 class="text-dark fw-bolder mb-7">Authenticator Apps</h3>
                            <div class="text-gray-500 fw-bold fs-6 mb-10">Using an authenticator app like 
                                <a href="https://support.google.com/accounts/answer/1066447?hl=en" target="_blank">Google Authenticator</a>, 
                                <a href="https://www.microsoft.com/en-us/account/authenticator" target="_blank">Microsoft Authenticator</a>, 
                                <a href="https://authy.com/download/" target="_blank">Authy</a>, or 
                                <a href="https://support.1password.com/one-time-passwords/" target="_blank">1Password</a>, scan the QR code. It will generate a 6 digit code for you to enter below. 
                                <div class="pt-5 text-center">
                                    <div v-html="page.qrcode"></div>
                                </div>
                            </div>
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6">
                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                        <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                        <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                    </svg>
                                </span>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-bold">
                                        <div class="fs-6 text-gray-700">
                                            If you having trouble using the QR code, select manual entry on your app, and enter your username and the code: 
                                            <div class="fw-bolder text-dark pt-2">{{ code }}</div>
                                            <div class="mt-2">
                                                Please store this recovery code in a secure location.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form">
                                <div class="d-flex justify-content-between">
                                    <button @click="enableSMS" class="btn btn-primary me-3">Enable SMS Authentication</button>
                                    <button @click="cancel" class="btn btn-light me-3">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div v-if="authOption == 'sms'">
                            <h3 class="text-dark fw-bolder fs-3 mb-5">SMS Authentication Enabled</h3>
                            <div class="text-muted fw-bold mb-10">We will send you a verification code to this number everytime you logged in.</div>
                            <div class="form">
                                <div class="mb-10 fv-row">
                                    <BaseInput 
                                        v-model="page.authuser.sms_auth_number"
                                        label=""
                                        type="text"
                                        id="contact_number"
                                        placeholder="Mobile number with country code..."
                                    />
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button @click="cancel" class="btn btn-light me-3">Cancel</button>
                                    <div>
                                        <button @click="enable2FA" class="btn btn-primary me-3" v-if="page.authuser.two_factor_secret == null">Enable 2FA Authentication</button> 
                                        <button type="submit" class="btn btn-primary" @click="saveSmsAuthNumber">
                                            <span class="indicator-label">Save Changes</span>
                                            <span class="indicator-progress">Please wait... 
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue';
import sourceRepo from '@/repositories/settings/source';
import authRepo from '@/repositories/settings/auth';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        }
    },
    setup(props, {emit}) {
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: false,
            isTwoFactorEnabled: false,
            qrcode: localStorage.getItem('auth-qrcode')
        })
        const isSuccess = ref(false);
        const authOption = ref('apps');
        const onSelected = ref(false);
        const { errors, status, storeSource, updateSource } = sourceRepo();
        const { errors: autherrors, status: authstatus, twoFASMSEnable, twoFAEnable, twoFADisable, code, getUser, updateAuthentication } = authRepo();

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('name', props.source.name ?? '');
            formData.append('agency_id', page.authuser.agency_id);

            if(props.source.id) {
                formData.append('_method', 'PUT');
                formData.append('id', props.source.id);
                await updateSource(formData, props.source.id);
            } else {
                await storeSource(formData);
            }
            
            isSuccess.value = true;
            if(status.value == 200) {
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const selectOption = async () => {
            page.isLoading = true;
            onSelected.value = true;
            if(authOption.value == 'apps') {
                await twoFAEnable(page);
            } else {
                page.isLoading = false;
            }
        }

        const cancel = () => {
            errors.value = [];
            onSelected.value = false;
            if(page.authuser.two_factor_secret) {
                authOption.value = 'apps';
            } else {
                authOption.value = 'sms';
            }
            emit('close-modal');
            emit('refresh-table');
        }

        const enableSMS = () => {
            authOption.value = 'sms';
        }

        const saveSmsAuthNumber = async () => {
            let formData = new FormData();
            formData.append('sms_auth_number', page.authuser.sms_auth_number ?? '');
            formData.append('user_id', page.authuser.id);
            await updateAuthentication(formData, page);
            if(authstatus.value == 200) {
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const enable2FA = async () => {
            authOption.value = 'apps';
            page.isLoading = true;
            await twoFAEnable(page);
        }

        onMounted(async() => {
            page.isTwoFactorEnabled = (page.authuser.two_factor_secret != null || page.authuser.sms_authentication == 1);
            authOption.value = page.authuser.sms_authentication == 1 ? 'sms' : 'apps';
            await getUser(page);
        });

        return {
            page,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            storeSource,
            updateSource,
            authOption,
            selectOption,
            onSelected,
            autherrors,
            authstatus,
            twoFAEnable,
            twoFADisable,
            twoFASMSEnable,
            getUser,
            code,
            enableSMS,
            saveSmsAuthNumber,
            updateAuthentication,
            enable2FA
        }
    },
}
</script>