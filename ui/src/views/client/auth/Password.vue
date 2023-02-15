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
									<h1 class="text-dark mb-3">Forgot Password?</h1>
									<!--end::Title-->
									<!--begin::Link-->
									<div class="text-gray-400 fw-bold fs-4">Enter your email to reset your password.</div>
									<!--end::Link-->
								</div>
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="fv-row mb-10">
									<label class="form-label fw-bolder text-gray-900 fs-6">Email</label>
									<input class="form-control form-control-solid" type="email" placeholder="" v-model="state.email" autocomplete="off" />
								</div>
								<!--end::Input group-->
								<!--begin::Actions-->
								<div class="d-flex flex-wrap justify-content-center pb-lg-0">
									<button type="button" id="kt_password_reset_submit" class="btn btn-lg btn-primary fw-bolder me-4" @click="sendPasswordReset">
										<span class="indicator-label" v-if="!loggingIn">Submit</span>
										<span v-if="loggingIn">
											Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>
									<router-link class="btn btn-lg btn-light-primary fw-bolder" :to="{ name: 'login' }">Cancel</router-link>
								</div>
								<!--end::Actions-->
								<div class="alert alert-success d-flex align-items-center mt-5 p-5 mb-10" v-if="sucess_message != ''">
									<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
									<span class="svg-icon svg-icon-2hx svg-icon-success me-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
											<path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"></path>
										</svg>
									</span>
									<!--end::Svg Icon-->
									<div>
										<h4 class="mb-1 text-success">Great! &nbsp;</h4>
										<span>{{ sucess_message }}</span>
									</div>
								</div>
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
import { reactive, ref } from 'vue';
import axios from 'axios';

export default {
    setup() {
        const state = reactive({
            base_url: process.env.VUE_APP_URL,
			email: ''
        });
		const sucess_message = ref('');
		const loggingIn = ref(false);

		const sendPasswordReset = async () => {
			loggingIn.value = true;
			let formData = new FormData();
            formData.append('email', state.email ?? '');
			let response = await axios.post(`client/auth/send-password-link`, formData);
			if(response.status == 200) {
				sucess_message.value = response.data.message;
				state.email = '';
			}
			loggingIn.value = false;
		}

        return {
            state,
			sendPasswordReset,
			sucess_message,
			loggingIn
        }
    },
}
</script>