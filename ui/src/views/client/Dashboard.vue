<template>
    <div>
        <Header />
        <div class="content-wrapper">
            <div class="container-fluid container-p-y">
                <div id="kt_app_content_container" class="app-container container-fluid" style="padding-left: 100px !important;">
                    <div class="row g-5 g-xxl-10">
                        <!--begin::Col-->
                        <div class="col-xl-5 col-xxl-5 mb-xl-5 mb-xxl-10">
                            <!--begin::Card widget 4-->
                            <div class="card card-flush h-xl-100">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Info-->
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
                        <div class="col-xl-7 col-xxl-7 mb-5 mb-xxl-10">
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

                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';

export default {
    setup() {
        const page = reactive({
            base_url: process.env.VUE_APP_URL
        });

        return {
            page
        }
    },
}
</script>