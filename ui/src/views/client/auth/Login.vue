<template>
    <div id="kt_body" class="auth-bg app-blank">
        <div class="d-flex flex-column flex-root" id="kt_app_page">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative" style="height: 100vh;">
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
							<a href="../../index.html" class="py-9 pt-lg-20">
								<img alt="Logo" :src="`${page.base_url}/assets/media/logos/logo-2.png`" style="width: 150px" />
							</a>
							<h1 class="fw-bolder text-white fs-2qx pb-5 pb-md-10">Welcome to IRIS Online</h1>
							<p class="fw-bold fs-2 text-white">A software that manage and empower your
							<br />recruitment management process</p>
						</div>
						<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(../../assets/media/illustrations/sketchy-1/2.png)"></div>
					</div>
				</div>

				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<div class="w-lg-600px p-10 p-lg-15 mx-auto">
							<div class="form w-100" v-if="!page.isTwoFA">
								<div class="text-center mb-10">
									<h1 class="text-dark mb-3">Sign In to IRIS</h1>
								</div>
								<div class="fv-row mb-10">
									<label class="form-label fs-6 fw-bolder text-dark">Email</label>
									<input class="form-control form-control-lg form-control-solid" type="text" v-model="form.email" autocomplete="off" />
									<label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors.email">{{ errors.email[0] }}</label>
								</div>
								<div class="fv-row mb-10">
									<div class="d-flex flex-stack mb-2">
										<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
										<router-link class="link-primary fs-6 fw-bolder" :to="{ name: 'password.reset' }">Forgot Password ?</router-link>
									</div>
									<input class="form-control form-control-lg form-control-solid" type="password" v-model="form.password" autocomplete="off" />
									<label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors.password">{{ errors.password[0] }}</label>
								</div>
								<div class="text-center">
									<button type="button" class="btn btn-lg btn-primary w-100 mb-5" @click="submit">
										<span class="indicator-label" v-if="!loggingIn">Continue</span>
										<span v-if="loggingIn">
											Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>

									<!-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div>
									<a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
									<img alt="Logo" :src="`${page.base_url}/assets/media/svg/brand-logos/google-icon.svg`" class="h-20px me-3" />Continue with Google</a>
									<a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
									<img alt="Logo" :src="`${page.base_url}/assets/media/svg/brand-logos/facebook-4.svg`" class="h-20px me-3" />Continue with Facebook</a> -->
									<div class="alert alert-danger d-flex align-items-center p-5 mb-10" v-if="hasErrorMsg">
										<!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
										<span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
												<path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"></path>
											</svg>
										</span>
										<!--end::Svg Icon-->
										<div class="d-flex">
											<h4 class="mb-1 text-danger">Oops! &nbsp;</h4>
											<span>{{ error_message }}</span>
										</div>
									</div>
									<div class="alert alert-success d-flex align-items-center p-5 mb-10" v-if="success_message">
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
											<span>{{ success_message }}</span>
										</div>
									</div>
								</div>
							</div>
							<div v-else>
								<div class="form w-100 mb-10">
									<div v-if="user.two_factor_secret">
										<div class="text-center mb-10">
											<h1 class="text-dark mb-3">Two Step Authentication</h1>
											<div class="text-muted fw-bold fs-5 mb-5">{{ (twofactortype == 'code') ? `Enter the 6 digit authentication code` : `Enter your recovery code` }}</div>
										</div>
									</div>
									<div v-else>
										<div class="text-center mb-10">
											<img alt="Logo" class="mh-125px" src="/assets/media/svg/misc/smartphone.svg" />
										</div>
										<div class="text-center mb-10">
											<h1 class="text-dark mb-3">Two Step Verification</h1>
											<div class="text-muted fw-bold fs-5 mb-5">Enter the verification code we sent to your mobile number</div>
											<!-- <div class="fw-bolder text-dark fs-3">******7859</div> -->
										</div>
									</div>
									<div class="mb-10 px-md-10" v-if="twofactortype == 'code' || twofactortype == 'sms_code'">
										<div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit security code</div>
										<CodeInput
											@complete="completeCode"
											:fields="6"
											:fieldWidth="56"
											:fieldHeight="56"
											:required="true"
										/>
									</div>
									<div class="mb-10 px-md-10" v-else>
										<div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Type your recovery code</div>
										<input class="form-control form-control-lg form-control-solid" type="text" v-model="authentication_code" autocomplete="off" />
									</div>
									<div class="d-flex flex-center">
										<a href="/auth/login" class="btn btn-lg btn-light-primary fw-bolder">Cancel</a> &nbsp;&nbsp;
										<button type="button" class="btn btn-lg btn-primary fw-bolder" :disabled="!completed" @click="challengeLogin">
											<span class="indicator-label">Submit</span>
											<span v-if="loggingIn">
												Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
											</span>
										</button>
									</div>
								</div>
								<div class="text-center fw-bold fs-5" v-if="user.two_factor_secret">
									<div v-if="twofactortype == 'recovery_code'">
										<a href="javascript:;" class="link-primary fw-bolder fs-5 me-1" @click="enterCode">Input Authentication Code</a>
									</div>
									<div v-else>
										<a href="javascript:;" class="link-primary fw-bolder fs-5 me-1" @click="enterRecovery">Input Recovery Code</a>
									</div>
								</div>
								<div class="text-center fw-bold fs-5" v-else>
									<span class="text-muted me-1">Didn't get the code ?</span>
									<a href="#" class="link-primary fw-bolder fs-5 me-1">Resend</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
						<div class="d-flex flex-center fw-bold fs-6">
							<a href="#" class="text-muted text-hover-primary px-2" target="_blank">About</a>
							<a href="#" class="text-muted text-hover-primary px-2" target="_blank">Support</a>
							<a href="#" class="text-muted text-hover-primary px-2" target="_blank">Purchase</a>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import axios from 'axios';
import CodeInput from "@/components/modules/Codeinput.vue";

export default {
	components: {
		CodeInput
	},
	data() {
        return {
            form: {
                email: '',
                password: ''
            },
            loggingIn: false,
            errors: {},
            user: {},
			page: {
				base_url: process.env.VUE_APP_URL,
				isTwoFA: false,
				qrcode: null,
				isTwoFactorEnabled: false
			},
			completed: false,
			authentication_code: '',
			twofactortype: 'code',
			hasErrorMsg: false,
			error_message: '',
			success_message: ''
        }
    },
    methods: {
        ...mapActions({
            signIn: "auth/signIn"
        }),
        submit() {
            this.loggingIn = true
            this.signIn(this.form)
            .then((authuser) => {
				this.user = authuser;
				if(authuser.two_factor_confirmed_at != null && authuser.two_factor_until == null) {
					this.page.isTwoFA = true;
					this.loggingIn = false;
				} else if(authuser.sms_authentication == 1 && authuser.two_factor_until == null) {
					this.page.isTwoFA = true;
					this.loggingIn = false;
					this.twofactortype = 'sms_code';
				} else {
					setTimeout(() => {
						window.location.href = '/client/dashboard';
					}, 2000);
				}
            })
            .catch(error => {
                this.loggingIn = false
                if (error.response.status == 422){
                    this.errors = error.response.data.errors;
                }
            });
        },
		completeCode(event) {
			this.completed = true;
			this.authentication_code = event;
		},
		enterRecovery() {
            this.twofactortype = 'recovery_code';
			this.completed = true;
			this.authentication_code = '';
        },
        enterCode() {
            this.twofactortype = 'code';
			this.completed = false;
        },
		async challengeLogin() {
			let response = await axios.post(`client/two-factor-authentication/challenge`, {
				user_id: this.user.id,
				code: this.authentication_code,
				type: this.twofactortype
			});

			if(response.status == 422) {
				this.errors = response.data.errors;
			} else {
				if(response.status == 200) {
					localStorage.setItem('authuser', JSON.stringify(response.data.user));
					localStorage.setItem('auth-qrcode', response.data.qrcode);
					window.location.href = '/client/dashboard';
				} else {
					localStorage.removeItem('authuser');
					localStorage.removeItem('auth-qrcode');
					localStorage.removeItem('token');
					window.location.href = `/auth/login?msg=${response.data.errors.code[0]}`;
				}
			}
		}
    },
	mounted() {
		if(this.$route.query.msg) {
			this.hasErrorMsg = true;
			this.error_message = this.$route.query.msg;
		}
	}
}
</script>