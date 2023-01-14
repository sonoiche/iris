<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 90%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Applicant Overview</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-12">
                                                    <loading v-if="state.isLoading" />
                                                    <div class="d-flex" v-else>
                                                        <div class="mr-15">
                                                            <img :src="applicant.display_photo" alt="IRIS" class="img-fluid profile-photo">
                                                        </div>
                                                        <div>
                                                            <span class="fw-bolder text-muted mr-10">Name:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.fullname }} - {{ applicant.applicant_number }}</span><br>
                                                            <span class="fw-bolder text-muted mr-10">Contact Number:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.mobile_number }}</span><br>
                                                            <span class="fw-bolder text-muted mr-10">Status:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.lineup_status }}</span><br>
                                                            <span class="fw-bolder text-muted mr-10">Lineup to:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.joborder }}</span><br>
                                                            <span class="fw-bolder text-muted mr-10">Principal:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.principal_name }}</span><br>
                                                            <span class="fw-bolder text-muted mr-10">Position Applied:</span> <span class="fw-bold fs-6 text-gray-800">{{ applicant.position_applied }}</span><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-12">
                                                    <h4>Recent Activities</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title">
                                            <a class="btn btn-primary btn-sm me-2 br-0" @click="viewComponent('ApplicantOverview')">Overview</a>
                                            <button class="btn btn-primary btn-sm me-2 br-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Profile</button>
                                            <ul class="dropdown-menu">
                                                <li><router-link class="dropdown-item" :to="{ name: 'client.applicant.edit', param: { id: applicant.applicant_id } }">Edit Information</router-link></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantEducation')">Manage Education</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantEmployment')">Manage Employment</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantLicense')">Manage Licenses</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantSkill')">Manage Skills</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantTraining')">Manage Training & Seminar</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" @click="viewComponent('ApplicantReference')">Manage Reference</a></li>
                                            </ul>
                                            <button class="btn btn-primary btn-sm me-2 br-0" @click="viewComponent('ApplicantDocument')">Document</button>
                                            <button class="btn btn-primary btn-sm me-2 br-0" @click="viewComponent('ApplicantMedical')">Medical</button>
                                            <button class="btn btn-primary btn-sm me-2 br-0" @click="viewComponent('ApplicantLineup')">Lineup</button>
                                            <button class="btn btn-primary btn-sm me-2 br-0" @click="viewComponent('ApplicantProcessing')">Processing</button>
                                            <!-- <button class="btn btn-primary btn-sm me-2 br-0">Payment</button> -->
                                            <button class="btn btn-primary btn-sm me-2 br-0">Remarks</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <component :is="currentComponent" :update-id="state.updateId" @add-data="changeComponent"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import applicantRepo from '@/repositories/applicants/applicant';
import { ref, onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';
import ApplicantOverview from '@/views/client/applicant/components/ApplicantOverview.vue';
import ApplicantEducation from '@/views/client/applicant/education/Index.vue';
import ApplicantCreateEducation from '@/views/client/applicant/education/Create.vue';
import ApplicantEditEducation from '@/views/client/applicant/education/Edit.vue';
import ApplicantEmployment from '@/views/client/applicant/employment/Index.vue';
import ApplicantCreateEmployment from '@/views/client/applicant/employment/Create.vue';
import ApplicantEditEmployment from '@/views/client/applicant/employment/Edit.vue';
import ApplicantLicense from '@/views/client/applicant/license/Index.vue';
import ApplicantCreateLicense from '@/views/client/applicant/license/Create.vue';
import ApplicantEditLicense from '@/views/client/applicant/license/Edit.vue';
import ApplicantSkill from '@/views/client/applicant/skill/Index.vue';
import ApplicantCreateSkill from '@/views/client/applicant/skill/Create.vue';
import ApplicantEditSkill from '@/views/client/applicant/skill/Edit.vue';
import ApplicantTraining from '@/views/client/applicant/trainings/Index.vue';
import ApplicantCreateTraining from '@/views/client/applicant/trainings/Create.vue';
import ApplicantEditTraining from '@/views/client/applicant/trainings/Edit.vue';
import ApplicantReference from '@/views/client/applicant/reference/Index.vue';
import ApplicantCreateReference from '@/views/client/applicant/reference/Create.vue';
import ApplicantEditReference from '@/views/client/applicant/reference/Edit.vue';
import ApplicantDocument from '@/views/client/applicant/document/Index.vue';
import ApplicantCreateDocument from '@/views/client/applicant/document/Create.vue';
import ApplicantEditDocument from '@/views/client/applicant/document/Edit.vue';
import ApplicantMedical from '@/views/client/applicant/medical/Index.vue';
import ApplicantCreateMedical from '@/views/client/applicant/medical/Create.vue';
import ApplicantEditMedical from '@/views/client/applicant/medical/Edit.vue';
import ApplicantLineup from '@/views/client/applicant/lineup/Index.vue';
import ApplicantProcessing from '@/views/client/applicant/processing/Index.vue';

export default {
    setup() {
        const route = useRoute();
        const { applicant, getApplicant } = applicantRepo();
        const state = reactive({
            isLoading: true,
            updateId: '',
            applicant_id: route.params.id
        });
        const currentComponent = ref('ApplicantOverview');

        const viewComponent = (component) => {
            currentComponent.value = component;
        }

        const changeComponent = (component, id = '') => {
            currentComponent.value = component;
            state.updateId = id;
        }

        onMounted( async () => {
            await getApplicant(route.params.id);
            state.isLoading = false;
        });

        return {
            applicant,
            state,
            getApplicant,
            currentComponent,
            viewComponent,
            changeComponent
        }
    },
    components: {
        ApplicantOverview,
        ApplicantEducation,
        ApplicantCreateEducation,
        ApplicantEditEducation,
        ApplicantEmployment,
        ApplicantCreateEmployment,
        ApplicantEditEmployment,
        ApplicantLicense,
        ApplicantCreateLicense,
        ApplicantEditLicense,
        ApplicantSkill,
        ApplicantCreateSkill,
        ApplicantEditSkill,
        ApplicantTraining,
        ApplicantCreateTraining,
        ApplicantEditTraining,
        ApplicantReference,
        ApplicantCreateReference,
        ApplicantEditReference,
        ApplicantDocument,
        ApplicantCreateDocument,
        ApplicantEditDocument,
        ApplicantMedical,
        ApplicantCreateMedical,
        ApplicantEditMedical,
        ApplicantLineup,
        ApplicantProcessing
    }
}
</script>

<style scoped>
.profile-photo {
    width: 150px;
    height: 150px;
    object-fit: cover;
}
.mr-15 {
    margin-right: 15px;
}
.mr-10 {
    margin-right: 10px;
}
.dropdown-toggle::after {
    margin-left: 10px;
    vertical-align: 2px;
}
.dropdown-item {
    font-size: 14px;
}
</style>