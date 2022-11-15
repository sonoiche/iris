<template>
    <div class="flex-md-row-fluid ms-lg-12">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="fw-bolder m-0">Account Settings</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex align-items-start flex-wrap">
                        <div class="d-flex flex-wrap">
                            <div class="symbol symbol-125px mb-7 me-7 position-relative">
                                <img :src="`${page.authuser.display_photo}`" alt="image" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fs-2 fw-bolder mb-1">{{ page.authuser.fullname }}</div>
                                <a href="#" class="text-gray-400 text-hover-primary fs-6 fw-bold mb-5">{{ page.authuser.email }}</a>
                                <div class="badge badge-light-success text-success fw-bolder fs-7 me-auto mb-3 px-4 py-3">Default</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Sign-in Method</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body border-top p-9">
                    <div class="d-flex flex-wrap align-items-center">
                        <div id="kt_signin_email" v-if="!page.isChangeEmail">
                            <div class="fs-6 fw-bolder mb-1">Email Address</div>
                            <div class="fw-bold text-gray-600">{{ page.authuser.email }}</div>
                        </div>
                        <div class="flex-row-fluid" v-if="page.isChangeEmail">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="form.email"
                                            label="Enter New Email Address"
                                            type="email"
                                            id="email"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-6">
                                        <BaseInput
                                            v-model="form.password"
                                            label="Confirm Password"
                                            type="password"
                                            id="password"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <base-button :success="isSuccess" :btnText="`Update Email`" @submit-form="updateEmail" />
                                    <button type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6" @click="changeEmail">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto" v-else>
                            <button class="btn btn-light btn-active-light-primary" @click="changeEmail">Change Email</button>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-6"></div>
                    <div class="d-flex flex-wrap align-items-center mb-10">
                        <div v-if="!page.isResetPassword">
                            <div class="fs-6 fw-bolder mb-1">Password</div>
                            <div class="fw-bold text-gray-600">************</div>
                        </div>
                        <div class="flex-row-fluid" v-if="page.isResetPassword">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        <BaseInput
                                            v-model="page.current_password"
                                            label="Current Password"
                                            type="password"
                                            id="current_password"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-4">
                                        <BaseInput
                                            v-model="form.password"
                                            label="New Password"
                                            type="password"
                                            id="password"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-4">
                                        <BaseInput
                                            v-model="page.password_confirmation"
                                            label="Confirm New Password"
                                            type="password"
                                            id="password_confirmation"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="form-text mb-5">Password must be at least 8 character and contain uppercase letter, lowercase letter, number and symbol</div>
                                <div class="d-flex">
                                    <base-button :success="isSuccess" :btnText="`Update Password`" @submit-form="updatePassword" />
                                    <button type="button" class="btn btn-color-gray-400 btn-active-light-primary px-6" @click="changePassword">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto" v-else>
                            <button class="btn btn-light btn-active-light-primary" @click="changePassword">Change Password</button>
                        </div>
                    </div>
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                        <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    opacity="0.3"
                                    d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                    fill="currentColor"
                                ></path>
                                <path
                                    d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-bold">
                                <h4 class="text-gray-900 fw-bolder">Secure Your Account</h4>
                                <div class="fs-6 text-gray-700 pe-7" v-if="page.authuser.two_factor_secret">You have enabled your two factor authentication.</div>
                                <div class="fs-6 text-gray-700 pe-7" v-if="page.authuser.sms_authentication == 1">You have enabled your two factor sms authentication.</div>
                                <div class="fs-6 text-gray-700 pe-7" v-else>Two-factor authentication adds an extra layer of security to your account. To log in, in addition you'll need to provide a 6 digit code</div>
                            </div>
                            <button class="btn btn-primary px-6 align-self-center text-nowrap" @click="enable2FA">{{ (page.authuser.two_factor_secret || page.authuser.sms_authentication == 1) ? `View` : `Enable` }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0" aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Profile Details</h3>
                </div>
            </div>
            <div class="collapse show">
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="card-body border-top p-9">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px">
                                        <img :src="photo_url" class="img-fluid">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change"
                                        data-bs-toggle="tooltip"
                                        title=""
                                        data-bs-original-title="Change avatar"
                                    >
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" accept=".png, .jpg, .jpeg" @change="onFileChange" multiple enctype="multipart/form-data" />
                                    </label>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel"
                                        data-bs-toggle="tooltip"
                                        title=""
                                        data-bs-original-title="Cancel avatar"
                                    >
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove"
                                        @click="removePhoto"
                                        data-bs-toggle="tooltip"
                                        title=""
                                        data-bs-original-title="Remove avatar"
                                    >
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-bold fs-6">Full Name</label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                        <BaseInput
                                            v-model="form.fname"
                                            label=""
                                            type="text"
                                            id="fname"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                        <BaseInput
                                            v-model="form.lname"
                                            label=""
                                            type="text"
                                            id="lname"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-bold fs-6">
                                <span>Contact Phone</span>
                            </label>
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <BaseInput
                                    v-model="form.contact_number"
                                    label=""
                                    type="text"
                                    id="contact_number"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                        <base-button :success="isSuccess" @submit-form="saveAccount" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_deactivate" aria-expanded="true" aria-controls="kt_account_deactivate">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Deactivate Account</h3>
                </div>
            </div>
            <div id="kt_account_settings_deactivate" class="collapse show">
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="card-body border-top p-9">
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                            <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                </svg>
                            </span>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-bold">
                                    <h4 class="text-gray-900 fw-bolder">You Are Deactivating Your Account</h4>
                                    <div class="fs-6 text-gray-700">
                                        For extra security, this requires you to confirm your email or phone number when you reset yousignr password.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-solid fv-row fv-plugins-icon-container">
                            <input name="deactivate" class="form-check-input" type="checkbox" v-model="page.deactivate" value="1" id="deactivate" />
                            <label class="form-check-label fw-bold ps-2 fs-6" for="deactivate">I confirm my account deactivation</label>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button class="btn btn-danger fw-bold" @click="deactivateAccount">Deactivate Account</button>
                    </div>
                </div>
            </div>
        </div>
        <ModalAuthentication :is-active="modalActive" @close-modal="closeModalAuthentication" />
    </div>
</template>

<script>
import { reactive, ref, inject } from 'vue';
import userRepo from '@/repositories/settings/users';
import { useRouter } from 'vue-router';
import ModalAuthentication from '@/views/client/settings/config/modals/Authentication.vue';

export default {
    setup() {
        const router = useRouter();
        const swal = inject('$swal');
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isChangeEmail: false,
            isResetPassword: false,
            current_password: '',
            password_confirmation: '',
            deactivate: false
        });

        const form = page.authuser;
        const photo_url = ref(page.authuser.display_photo);
        const profile_photo = ref('');
        const isSuccess = ref(false);
        const modalActive = ref(false);

        const { updateUserEmail, updateUserPassword, updateUser, destroyUser, status, errors } = userRepo();

        const changeEmail = () => {
            page.isChangeEmail = !page.isChangeEmail;
            form.password = '';
        }

        const changePassword = () => {
            page.isResetPassword = !page.isResetPassword;
            form.password = '';
        }

        const updateEmail = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('email', form.email ?? '');
            formData.append('password', form.password ?? '');
            formData.append('user_id', form.id);
            formData.append('_method', 'PUT');

            await updateUserEmail(formData, form.id);
            if(status.value == 200) {
                changeEmail();
            }
            isSuccess.value = true;
        }

        const updatePassword = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('password', form.password ?? '');
            formData.append('password_confirmation', page.password_confirmation ?? '');
            formData.append('current_password', page.current_password ?? '');
            formData.append('user_id', form.id);
            formData.append('_method', 'PUT');

            await updateUserPassword(formData, form.id);
            if(status.value == 200) {
                changePassword();
            }
            isSuccess.value = true;
        }

        const saveAccount = async () => {
            isSuccess.value = false;
            let formData = new FormData();
            formData.append('fname', form.fname ?? '');
            formData.append('lname', form.lname ?? '');
            formData.append('contact_number', form.contact_number ?? '');
            formData.append('photo', profile_photo.value ?? '');
            formData.append('_method', 'PUT');

            await updateUser(formData, page.authuser.id);
            isSuccess.value = true;
        }

        const onFileChange = (e) => {
            const file = e.target.files[0];
            photo_url.value = URL.createObjectURL(file);
            profile_photo.value = file;
        }

        const removePhoto = () => {
            photo_url.value = page.authuser.display_photo;
            profile_photo.value = '';
        }

        const deactivateAccount =() => {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, deactivate my account!'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    if(page.deactivate == true) {
                        await destroyUser(page.authuser.id);
                        if(status.value == 200) {
                            localStorage.removeItem('token');
                            localStorage.removeItem('authuser');
                            router.go();
                        }
                    } else {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'You need to confirm the deactivation of your account.'
                        });
                    }
                }
            });
        }

        const closeModalAuthentication = () => {
            modalActive.value = false;
        }

        const enable2FA = () => {
            modalActive.value = true;
        }

        return {
            page,
            form,
            photo_url,
            profile_photo,
            isSuccess,
            changeEmail,
            changePassword,
            updateEmail,
            updatePassword,
            updateUserEmail,
            updateUserPassword,
            updateUser,
            destroyUser,
            saveAccount,
            onFileChange,
            removePhoto,
            deactivateAccount,
            enable2FA,
            modalActive,
            closeModalAuthentication,
            status,
            errors
        }
    },
    components: {
        ModalAuthentication
    }
}
</script>