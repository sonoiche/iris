<template>
    <div>
        <modal :active="isActive" :size="`lg`">
            <template #header>
                <loading v-if="isLoading" />
                <div v-else class="mb-13 text-center">
                    <h1 class="mb-3">Upload Applicant Photo</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-6 col-md-12">
                            <div data-kt-buttons="true">
                                <!--begin::Radio button-->
                                <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-6 mb-5 active">
                                    <!--end::Description-->
                                    <div class="d-flex align-items-center me-2">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                            <input class="form-check-input" type="radio" v-model="upload_type" checked="checked" value="photo"/>
                                        </div>
                                        <!--end::Radio-->

                                        <!--begin::Info-->
                                        <div class="flex-grow-1">
                                            <h2 class="d-flex align-items-center fs-3 fw-bolder flex-wrap">
                                                Upload a Photo
                                            </h2>
                                            <div class="fw-bold opacity-50">
                                                Get image file from your device
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Description-->
                                </label>
                                <!--end::Radio button-->
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div data-kt-buttons="true">
                                <!--begin::Radio button-->
                                <label class="btn btn-outline btn-outline-dashed d-flex flex-stack text-start p-6 mb-5">
                                    <!--end::Description-->
                                    <div class="d-flex align-items-center me-2">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                            <input class="form-check-input" type="radio" v-model="upload_type" value="camera"/>
                                        </div>
                                        <!--end::Radio-->

                                        <!--begin::Info-->
                                        <div class="flex-grow-1">
                                            <h2 class="d-flex align-items-center fs-3 fw-bolder flex-wrap">
                                                Capture Photo
                                            </h2>
                                            <div class="fw-bold opacity-50">
                                                Capture photo using webcam
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Description-->
                                </label>
                                <!--end::Radio button-->
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12" v-if="upload_type == 'photo'">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="photo" class="form-label fs-6 fw-bolder mb-3">Image File</label>
                                <input
                                    type="file"
                                    class="form-control form-control form-control-solid"
                                    id="photo"
                                    @change="onFileChange"
                                    enctype="multipart/form-data"
                                />
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" v-else>
                            <div v-show="state.isCameraOpen && state.isCameraLoading" class="camera-loading">
                                <loading />
                            </div>

                            <div v-if="state.isCameraOpen" v-show="!state.isCameraLoading" class="camera-box d-flex justify-content-center" :class="{ 'flash' : state.isShotPhoto }">
                                <div class="camera-shutter" :class="{'flash' : state.isShotPhoto}"></div>
                                <video v-show="!state.isPhotoTaken" ref="camera" :width="450" :height="337.5" autoplay></video>
                                <canvas v-show="state.isPhotoTaken" id="photoTaken" ref="canvas" :width="450" :height="337.5"></canvas>
                            </div>

                            <div v-if="state.isCameraOpen && !state.isCameraLoading" class="camera-shoot d-flex justify-content-center">
                                <button type="button" class="button" @click="takePhoto">
                                    <img src="https://img.icons8.com/material-outlined/50/000000/camera--v2.png" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" :btnText="`Upload Photo`" @submit-form="saveChanges" />

            </template>
        </modal>
    </div>
</template>

<script>
import { reactive, ref, watch } from 'vue';
import applicantRepo from '@/repositories/applicants/applicant';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        applicant_id: {
            type: String,
            default: ''
        },
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true,
            role_id: '',
            isCameraOpen: false,
            isPhotoTaken: false,
            isShotPhoto: false,
            isCameraLoading: false,
            isActivePhoto: false
        })
        const isSuccess = ref(false);
        const { errors, status, uploadApplicantPhoto } = applicantRepo();
        const upload_type = ref('photo');
        const camera = ref('camera');
        const canvas = ref('canvas');
        const image = ref('');

        const onFileChange = (e) => {
            const file = e.target.files[0];
            image.value = file;
        }

        const saveChanges = async () => {
            isSuccess.value = false;
            let formData = new FormData();

            formData.append('upload_type', upload_type.value ?? '');
            formData.append('applicant_id', props.applicant_id ?? '');
            if(upload_type.value == 'photo') {
                formData.append('image', image.value ?? '');
            }
            if(upload_type.value == 'camera') {
                let canvas = document.getElementById("photoTaken").toDataURL("image/png");
                formData.append('canvas', canvas ?? '');
            }

            await uploadApplicantPhoto(formData);
            
            isSuccess.value = true;
            if(status.value == 200) {
                upload_type.value = 'photo';
                emit('close-modal');
                emit('refresh-table');
            }
        }

        const cancel = () => {
            errors.value = [];
            upload_type.value = 'photo';
            emit('close-modal');
        }

        const createCameraElement = () => {
            state.isCameraLoading = true;
        
            const constraints = (window.constraints = {
                audio: false,
                video: true
            });

            navigator.mediaDevices
                .getUserMedia(constraints)
                .then(stream => {
                    state.isCameraLoading = false;
                    camera.value.srcObject = stream;
                })
                .catch(error => {
                    state.isCameraLoading = false;
                    alert("May the browser didn't support or there is some errors.");
                });
        }

        const stopCameraStream = () => {
            state.isActivePhoto = false;
            let tracks = camera.value.srcObject.getTracks();
            tracks.forEach(track => {
                track.stop();
            });
        }

        const takePhoto = () => {
            if(!state.isPhotoTaken) {
                state.isShotPhoto = true;
                state.isActivePhoto = true;

                const FLASH_TIMEOUT = 50;

                setTimeout(() => {
                    state.isShotPhoto = false;
                }, FLASH_TIMEOUT);
            }
            
            state.isPhotoTaken = !state.isPhotoTaken;
            
            const context = canvas.value.getContext('2d');
            context.drawImage(camera.value, 0, 0, 450, 337.5);
        }

        watch(() => upload_type.value, () => {
            if(upload_type.value == 'camera') {
                state.isCameraOpen = true;
                createCameraElement(); 
            } else {
                state.isCameraOpen = false;
                state.isPhotoTaken = false;
                state.isShotPhoto = false;
                stopCameraStream();
            }
        });

        return {
            state,
            isSuccess,
            saveChanges,
            cancel,
            errors,
            status,
            uploadApplicantPhoto,
            onFileChange,
            upload_type,
            createCameraElement,
            camera,
            canvas,
            stopCameraStream,
            takePhoto,
            image
        }
    },
}
</script>

<style scoped>
.camera-button {
    margin-bottom: 2rem;
}
  
.camera-box > .camera-shutter {
    opacity: 0;
    width: 450px;
    height: 337.5px;
    background-color: #fff;
    position: absolute;
}
  
.camera-shoot {
    margin: 1rem 0;
}
.camera-shoot > button {
    height: 60px;
    width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
}

.camera-shoot > img {
    height: 35px;
    object-fit: cover;
}
</style>