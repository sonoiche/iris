<template>
    <div>
        <Header />
        <div class="content-wrapper" v-if="page.turnOff">
            <div class="container-fluid container-p-y">
                <div id="kt_app_content_container" class="app-container container-fluid" style="padding-left: 100px !important;">
                    <div class="row g-5 g-xxl-10">
                        <div class="col-xl-6 col-xxl-6 mb-xl-5 mb-xxl-10">
                            <div class="card card-flush">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">Quick Links</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                    <div class="col-4">
                                        <router-link :to="{ name: 'client.applicant.encode' }">
                                            <div class="card" style="margin-right: 5px;">
                                                <div class="card-body mx-auto">
                                                    <div class="nav-icon mb-3 text-center">
                                                        <i class="far fa-paper-plane fs-1 p-0"></i>
                                                    </div>
                                                    <span class="nav-text text-gray-800 fw-bolder fs-6 lh-1">Quick Encode</span>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                    <div class="col-4" style="margin: 0 5px;">
                                        <router-link :to="{ name: 'client.applicant.search' }">
                                            <div class="card mr-3">
                                                <div class="card-body mx-auto">
                                                    <div class="nav-icon mb-3 text-center">
                                                        <i class="fas fa-search fs-1 p-0"></i>
                                                    </div>
                                                    <span class="nav-text text-gray-800 fw-bolder fs-6 lh-1">Search Applicant</span>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                    <div class="col-4">
                                        <router-link :to="{ name: 'client.employer' }">
                                            <div class="card" style="margin-left: 5px;">
                                                <div class="card-body mx-auto">
                                                    <div class="nav-icon mb-3 text-center">
                                                        <i class="fas fa-user-tie fs-1 p-0"></i>
                                                    </div>
                                                    <span class="nav-text text-gray-800 fw-bolder fs-6 lh-1">Search Principal</span>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                    <!--end::Labels-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 4-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-xl-6 col-xxl-6 mb-5 mb-xxl-10">
                            <div class="card card-flush h-xl-100">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">Statistics</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 my-5">
                                            <div class="card">
                                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                                    <div class="d-flex my-3">
                                                        <div class="d-flex align-items-center" style="margin-right: 30px;">
                                                            <i class="fa fa-users fa-3x"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold fs-3x text-gray-800 lh-1 ls-n2">{{ applicants.length }}</span>
                                                            <div class="m-0">
                                                                <span class="fw-bold fs-6 text-gray-400">Encoded Applicants</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 my-5">
                                            <div class="card">
                                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                                    <div class="d-flex my-3">
                                                        <div class="d-flex align-items-center" style="margin-right: 30px;">
                                                            <i class="fas fa-building fa-3x"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold fs-3x text-gray-800 lh-1 ls-n2">{{ principals.length }}</span>
                                                            <div class="m-0">
                                                                <span class="fw-bold fs-6 text-gray-400">Encoded Principals</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 my-5">
                                            <div class="card">
                                                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                                    <div class="d-flex my-3">
                                                        <div class="d-flex align-items-center" style="margin-right: 30px;">
                                                            <i class="fas fa-street-view fa-3x"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold fs-3x text-gray-800 lh-1 ls-n2">{{ joborders.length }}</span>
                                                            <div class="m-0">
                                                                <span class="fw-bold fs-6 text-gray-400">Manpower Requests</span>
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
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <div class="row g-5 g-xxl-10">
                        <!--begin::Col-->
                        <div class="col-xl-6 col-xxl-6 mb-xl-5 mb-xxl-10">
                            <!--begin::Card widget 4-->
                            <div class="card card-flush">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">Recent Activity Logs</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                    <table class="table align-middle fs-6 gy-5 table-row-dashed">
                                        <thead>
                                            <tr>
                                                <th class="bordered text-center">#</th>
                                                <th class="bordered">Date/Time</th>
                                                <th class="bordered">Applicant Number</th>
                                                <th class="bordered">Applicant Name</th>
                                                <th class="bordered">Module</th>
                                                <th class="bordered">Action Taken</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(result, index) in results" :key="index">
                                                <td class="bordered text-center">{{ index+1 }}</td>
                                                <td class="bordered">{{ result.created_at_display }}</td>
                                                <td class="bordered">{{ result.applicant_id }}</td>
                                                <td class="bordered">{{ result.applicant_name }}</td>
                                                <td class="bordered">{{ result.module }}</td>
                                                <td class="bordered">{{ result.user_action }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-xxl-6 mb-xl-5 mb-xxl-10">
                            <!--begin::Card widget 4-->
                            <StatisticsChart />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, onMounted, ref } from 'vue';
import axios from 'axios';
import applicantRepo from '@/repositories/applicants/applicant';
import principalRepo from '@/repositories/employer/principal';
import joborderRepo from '@/repositories/employer/joborder';
import StatisticsChart from '@/components/StatisticsChart';

export default {
    components: {
        StatisticsChart
    },
    setup() {
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            authuser: JSON.parse(localStorage.getItem('authuser')),
            turnOff: false
        });
        const { applicants, getApplicants } = applicantRepo();
        const { principals, getSelectPrincipal } = principalRepo();
        const { joborders, getJobOrders } = joborderRepo();
        const results = ref([]);

        onMounted( async () => {
            getApplicants();
            getSelectPrincipal();
            getJobOrders();

            let response =  await axios.get(`client/activity-logs?user_id=${page.authuser.id}&limit=5`);
            results.value = response.data.data;
        });

        return {
            page,
            applicants,
            getApplicants,
            principals,
            getSelectPrincipal,
            joborders,
            getJobOrders,
            results
        }
    },
}
</script>