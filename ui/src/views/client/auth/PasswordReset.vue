<template>
    <div id="kt_body" class="auth-bg app-blank">
        <div class="d-flex flex-column flex-root" id="kt_app_page">
			<!--begin::Authentication - Password reset -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative" style="height: 100vh">
					<!--begin::Wrapper-->
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
						<!--begin::Header-->
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
							<a href="../../index.html" class="py-9 pt-lg-20">
								<img alt="Logo" :src="`${state.base_url}/assets/media/logos/logo-2.png`" style="width: 150px" />
							</a>
							<h1 class="fw-bolder text-white fs-2qx pb-5 pb-md-10">Welcome to IRIS Online</h1>
							<p class="fw-bold fs-2 text-white">A software that manage and empower your
							<br />recruitment management process</p>
						</div>
						<!--end::Header-->
						<!--begin::Illustration-->
						<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(../../assets/media/illustrations/sketchy-1/2.png)"></div>
						<!--end::Illustration-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--begin::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<!--begin::Content-->
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10 p-lg-15 mx-auto">
							<!--begin::Form-->
							<form class="form w-100" novalidate="novalidate" id="kt_password_reset_form">
								<!--begin::Heading-->
								<div class="text-center mb-10">
									<!--begin::Title-->
									<h1 class="text-dark mb-3">Reset Password</h1>
									<!--end::Title-->
									<!--begin::Link-->
									<div class="text-gray-400 fw-bold fs-4">Enter your your new password.</div>
									<!--end::Link-->
								</div>
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<label class="form-label fw-bolder text-gray-900 fs-6">Email</label>
									<input class="form-control form-control-solid" type="email" v-model="state.email" readonly />
								</div>
                                <div class="fv-row mb-10">
									<label class="form-label fw-bolder text-gray-900 fs-6">Password</label>
									<input class="form-control form-control-solid" type="password" v-model="state.password" autocomplete="off" />
                                    <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors.password">{{ errors.password[0] }}</label>
								</div>
                                <div class="fv-row mb-10">
									<label class="form-label fw-bolder text-gray-900 fs-6">Retype Password</label>
									<input class="form-control form-control-solid" type="password" v-model="state.password_confirmation" autocomplete="off" />
								</div>
								<!--end::Input group-->
								<!--begin::Actions-->
								<div class="d-flex flex-wrap justify-content-center pb-lg-0">
									<button type="button" id="kt_password_reset_submit" class="btn btn-lg btn-primary fw-bolder me-4" @click="resetPassword">
										<span class="indicator-label" v-if="!loggingIn">Submit</span>
										<span v-if="loggingIn">
											Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>
									<router-link class="btn btn-lg btn-light-primary fw-bolder" :to="{ name: 'login' }">Cancel</router-link>
								</div>
								<!--end::Actions-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Content-->
					<!--begin::Footer-->
					<div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
						<!--begin::Links-->
						<div class="d-flex flex-center fw-bold fs-6">
							<a href="https://irisonlie.app" class="text-muted text-hover-primary px-2" target="_blank">About</a>
							<a href="https://devs.keenthemes.com/" class="text-muted text-hover-primary px-2" target="_blank">Support</a>
							<a href="https://themes.getbootstrap.com/product/good-bootstrap-5-admin-dashboard-template" class="text-muted text-hover-primary px-2" target="_blank">Purchase</a>
						</div>
						<!--end::Links-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Password reset-->
		</div>
    </div>
</template>

<script>
import { reactive, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
    setup() {
        const route = useRoute();
        const router = useRouter();
        const state = reactive({
            base_url: process.env.VUE_APP_URL,
			email: '',
            password: '',
            password_confirmation: ''
        });
        const code = ref('');
        const errors = ref([]);
        const loggingIn = ref(false);

		const resetPassword = async () => {
            loggingIn.value = true;
            try {
                let formData = new FormData();
                formData.append('invite_token', code.value ?? '');
                formData.append('password', state.password ?? '');
                formData.append('password_confirmation', state.password_confirmation ?? '');
                let response = await axios.post(`client/auth/change-password`, formData);
                if(response.status == 200) {
                    router.push({
                        name: 'login'
                    });
                }
                loggingIn.value = false;
            } catch (e) {
                if(e.response.status === 422) {
                    errors.value = e.response.data.errors;
                    loggingIn.value = false;
                }
            }
		}

        onMounted( async () => {
            let response = await axios.get(`client/auth/get-password-reset/${route.params.code}`);
            state.email = response.data.user.email
            code.value  = response.data.user.invite_token;
        });

        return {
            state,
			resetPassword,
            code,
            errors,
            loggingIn
        }
    },
}
</script>