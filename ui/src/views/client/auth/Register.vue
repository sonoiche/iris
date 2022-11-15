<template>
    <div id="kt_body" class="auth-bg app-blank">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-auto bg-primary w-xl-600px positon-xl-relative">
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
							<a href="#" class="py-9 pt-lg-20">
								<img alt="Logo" :src="`${page.base_url}/assets/media/logos/default.svg`" class="h-40px" />
							</a>
							<h1 class="fw-bolder text-white fs-2qx pb-5 pb-md-10">Welcome to Good</h1>
							<p class="fw-bold fs-2 text-white">Plan your blog post by choosing a topic creating 
							<br />an outline and checking facts</p>
						</div>
						<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(/assets/media/illustrations/sketchy-1/2.png)"></div>
					</div>
				</div>
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<div class="w-lg-600px p-10 p-lg-15 mx-auto">
							<form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
								<div class="mb-10 text-center">
									<h1 class="text-dark mb-3">Create an Account</h1>
									<div class="text-gray-400 fw-bold fs-4">Already have an account? 
									<router-link :to="{ name: 'login' }" class="link-primary fw-bolder">Sign in here</router-link></div>
								</div>
								<button type="button" class="btn btn-light-primary fw-bolder w-100 mb-10">
								<img alt="Logo" src="/assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3" />Sign in with Google</button>
								<div class="d-flex align-items-center mb-10">
									<div class="border-bottom border-gray-300 mw-50 w-100"></div>
									<span class="fw-bold text-gray-400 fs-7 mx-2">OR</span>
									<div class="border-bottom border-gray-300 mw-50 w-100"></div>
								</div>
								<div class="row fv-row mb-7">
									<div class="col-xl-6">
										<label class="form-label fw-bolder text-dark fs-6">First Name</label>
										<input class="form-control form-control-lg form-control-solid" type="text" v-model="form.fname" autocomplete="off" />
                                        <div class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['fname']">
                                            <div data-validator="notEmpty">{{ errors['fname'][0] }}</div>
                                        </div>
									</div>
									<div class="col-xl-6">
										<label class="form-label fw-bolder text-dark fs-6">Last Name</label>
										<input class="form-control form-control-lg form-control-solid" type="text" v-model="form.lname" autocomplete="off" />
                                        <div class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['lname']">
                                            <div data-validator="notEmpty">{{ errors['lname'][0] }}</div>
                                        </div>
									</div>
								</div>
								<div class="fv-row mb-7">
									<label class="form-label fw-bolder text-dark fs-6">Email</label>
									<input class="form-control form-control-lg form-control-solid" type="email" v-model="form.email" readonly autocomplete="off" />
                                    <div class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['email']">
                                        <div data-validator="notEmpty">{{ errors['email'][0] }}</div>
                                    </div>
								</div>
								<div class="mb-10 fv-row">
									<div class="mb-1">
										<label class="form-label fw-bolder text-dark fs-6">Password</label>
										<div class="position-relative mb-3">
											<input class="form-control form-control-lg form-control-solid" :type="isPasswordHidden ? 'password' : 'text'" v-model="form.password" autocomplete="off" @input="passwordInput" />
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" @click="showPassword">
												<i class="bi bi-eye-slash fs-2" :class="{ 'd-none' : (isPasswordHidden == false) }"></i>
												<i class="bi bi-eye fs-2" :class="{ 'd-none' : (isPasswordHidden == true) }"></i>
											</span>
										</div>
                                        <div class="fv-plugins-message-container invalid-feedback mb-2" v-if="errors && errors['password']">
                                            <div data-field="first-name" data-validator="notEmpty">{{ errors['password'][0] }}</div>
                                        </div>
										<div class="d-flex align-items-center mb-3">
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{ 'active' : hasEightChars }"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{ 'active' : (hasEightChars && hasLetters) }"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2" :class="{ 'active' : (hasEightChars && hasLetters && hasNumbers) }"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px" :class="{ 'active' : (hasEightChars && hasLetters && hasNumbers && hasSymbols) }"></div>
										</div>
									</div>
									<div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
								</div>
								<div class="fv-row mb-5">
									<label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
									<input class="form-control form-control-lg form-control-solid" type="password" v-model="form.password_confirmation" autocomplete="off" />
								</div>
								<div class="fv-row mb-10">
									<label class="form-check form-check-custom form-check-solid form-check-inline">
										<input class="form-check-input" type="checkbox" name="toc" value="1" />
										<span class="form-check-label fw-bold text-gray-700 fs-6">I Agree 
										<a href="#" class="ms-1 link-primary">Terms and conditions</a>.</span>
									</label>
								</div>
								<div class="text-center">
									<button type="button" class="btn btn-lg btn-primary" @click="register">
										<span class="indicator-label" v-if="!loggingIn">Submit</span>
										<span v-else>
											Please wait... 
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>
								</div>
							</form>
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
import _debounce from 'lodash/debounce';

export default {
	data() {
        return {
            form: {
                fname: '',
                lname: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            loggingIn: false,
            errors: {},
            user: {},
			page: {
				base_url: process.env.VUE_APP_URL
			},
            hasEightChars: false,
            hasLetters: false,
            hasNumbers: false,
            hasSymbols: false,
            isPasswordHidden: true
        }
    },
    methods: {
        ...mapActions({
            signUp: "auth/signUp"
        }),
        register() {
            this.loggingIn = true
            this.signUp(this.form)
            .then((authuser) => {
                this.user = authuser;
				setTimeout(() => {
					window.location.href = '/client/dashboard';
				}, 2000);
            })
            .catch(error => {
                this.loggingIn = false
                if (error.response.status == 422){
                    this.errors = error.response.data.errors;
                }
            });
        },
        passwordInput: _debounce( async function () {
            let response = await axios.post(`client/auth/check-password`, {
                password: this.form.password
            });

            this.hasEightChars = response.data.hasEightChars;
            this.hasLetters = response.data.hasLetters;
            this.hasNumbers = response.data.hasNumbers;
            this.hasSymbols = response.data.hasSymbols;
        }, 500),
        showPassword() {
            this.isPasswordHidden = !this.isPasswordHidden;
        }
    },
	async mounted() {
		let response = await axios.post(`client/auth/check-invite-token`, {
			invite_token: this.$route.query.token
		});
		this.form = response.data.data;
	}
}
</script>