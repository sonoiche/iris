<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Add Document</h3>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-outline-success btn-sm" @click="backPage">Back</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <div class="d-grid gap-2 col-8 mx-auto">
                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="document.name"
                                            label="Document Name"
                                            type="text"
                                            id="name"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseSelect
                                            label="Document Type"
                                            :options="document_types"
                                            :placeholder="`Select Document Type`"
                                            :defaultValue="{ id: document.document_type, name: document.document_type_name }"
                                            id="document_type"
                                            :is-clear="isClear"
                                            @select-value="setDocumentType"
                                            :errors="errors"
                                            is-required
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label for="attachment" class="form-label fs-6 fw-bolder mb-3">Attachment <span class="text-danger">*</span></label>
                                        <input
                                            type="file"
                                            class="form-control form-control form-control-solid"
                                            :class="{ 'is-invalid' : errors && errors['attachment'] }"
                                            id="attachment"
                                            @change="onFileChange"
                                            multiple enctype="multipart/form-data"
                                        />
                                        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors['attachment']">{{ errors['attachment'][0] }}</label>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="document.document_number"
                                            label="Document Number"
                                            type="text"
                                            id="document_number"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <BaseInput
                                            v-model="document.place_issue"
                                            label="Place Issued"
                                            type="text"
                                            id="place_issue"
                                        />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Issued</label>
                                        <date-picker v-model="state.date_issue" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Expiry</label>
                                        <date-picker v-model="state.date_expiry" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Date Submitted</label>
                                        <date-picker v-model="state.date_submitted" inputClassName="form-control form-control-solid fc-calendar" :format="`MM/dd/yyyy`" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bolder mb-3">Remarks</label>
                                        <textarea rows="4" style="width: 100%" class="form-control form-control form-control-solid" v-model="document.remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-6 mt-3">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="d-flex justify-content-end">
                                            <base-button :success="isSuccess" :btn-text="`Save & Add Another`" @submit-form="saveChanges" /> &nbsp;&nbsp;
                                            <base-button :success="isContinue" :btn-text="`Save & Continue`" @submit-form="saveContinue" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import documentRepo from '@/repositories/applicants/document';
import documentTypeRepo from '@/repositories/settings/document_type';
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            date_issue: '',
            date_expiry: '',
            date_submitted: '',
            addContinue: false
        });
        const { documents, getDocuments } = documentTypeRepo();
        const { status, errors, document, storeDocument } = documentRepo();

        const attachment = ref('');
        const isSuccess = ref(false);
        const isContinue = ref(false);
        const isClear = ref(false);

        const document_types = computed(() => {
            const arr_types = [];
            documents.value.forEach(item => {
                arr_types.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_types;
        });

        const onFileChange = (e) => {
            const file = e.target.files[0];
            attachment.value = file;
        }

        const setDocumentType = (value) => {
            document.value.document_type = value.id;
            document.value.document_type_name = value.name;
        }

        const saveChanges = () => {
            isSuccess.value = false;
            submitForm();
        }

        const saveContinue = () => {
            isContinue.value = false;
            state.addContinue = true;
            submitForm();
        }

        const submitForm = async () => {
            let formData = new FormData();
            formData.append('name', document.value.name ?? '');
            formData.append('document_type', document.value.document_type ?? '');
            formData.append('attachment', attachment.value ?? '');
            formData.append('document_number', document.value.document_number ?? '');
            formData.append('place_issue', document.value.place_issue ?? '');
            formData.append('remarks', document.value.remarks ?? '');
            formData.append('date_issue', (state.date_issue) ? new Date(state.date_issue).toISOString() : '');
            formData.append('date_expiry', (state.date_expiry) ? new Date(state.date_expiry).toISOString() : '');
            formData.append('date_submitted', (state.date_submitted) ? new Date(state.date_submitted).toISOString() : '');
            formData.append('type', 'create');
            formData.append('applicant_id', route.params.id);
            await storeDocument(formData);
            isSuccess.value = true;
            isContinue.value = true;

            if(status.value == 200) {
                document.value = [];
                state.date_issue = '';
                state.date_expiry = '';
                state.date_submitted = '';
                isClear.value = true;
                $('#attachment').val('');
                if(state.addContinue) {
                    setTimeout(() => {
                        emit('add-data', 'ApplicantDocument');
                    }, 1000);
                    state.addContinue = false;
                }
            }
        }

        const backPage = () => {
            emit('add-data', 'ApplicantDocument');
        }

        onMounted( async () => {
            await getDocuments(state.authuser.agency_id);
        });

        return {
            state,
            status,
            errors,
            document,
            storeDocument,
            isSuccess,
            isContinue,
            saveChanges,
            saveContinue,
            submitForm,
            backPage,
            onFileChange,
            attachment,
            setDocumentType,
            documents,
            getDocuments,
            document_types,
            isClear
        }
    },
}
</script>