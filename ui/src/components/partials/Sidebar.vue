<template>
    <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
        <!--begin::Logo-->
        <div class="app-sidebar-logo d-none d-lg-flex flex-center py-10 px-5 mb-1" id="kt_app_sidebar_logo">
            <!--begin::Logo image-->
            <a>
                <img alt="Logo" :src="`${page.base_url}/assets/media/logos/logo.png`" class="h-40px" />
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Logo-->
        <!--begin::Sidebar menu-->
        <div class="app-sidebar-menu d-flex w-100 hover-scroll-overlay-y my-5" id="kt_app_sidebar_menu_wrapper" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention menu-active-bg menu-here-bg menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-bold" id="#kt_app_sidebar_menu" data-kt-menu="true">
                <router-link class="menu-item py-1" :to="{ name: 'client.dashboard' }">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-house fs-1"></i>
                        </span>
                    </span>
                </router-link>
                <div class="menu-item py-1" @mouseover="menuHover('employer')" @mouseleave="menuHoverLeave('employer')" :class="{ 'show menu-dropdown' : page.employerHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-user fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 171px);">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Employers</span>
                            </div>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Manpower Request')">
                            <router-link class="menu-link" :to="{ name: 'client.joborder' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Manpower Request</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('My Manpower Request')">
                            <router-link class="menu-link" :to="{ name: 'client.joborder.owned' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">My Manpower Request</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Employer Manager')">
                            <router-link class="menu-link" :to="{ name: 'client.employer' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Employer Manager</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="menu-item py-1" @mouseover="menuHover('applicant')" @mouseleave="menuHoverLeave('applicant')" :class="{ 'show menu-dropdown' : page.applicantHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-user-2 fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 238px);">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Applicants</span>
                            </div>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Add Applicant')">
                            <router-link class="menu-link" :to="{ name: 'client.applicant.create' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Add Applicant</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicant Pipeline')">
                            <router-link class="menu-link" :to="{ name: 'client.applicant.pipeline' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicant Pipeline</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Interview Schedule')">
                            <router-link class="menu-link" :to="{ name: 'client.interview' }">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Interview Schedule</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Quick Encode')">
                            <router-link :to="{ name: 'client.applicant.encode' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Quick Encode</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Search Applicant')">
                            <router-link :to="{ name: 'client.applicant.search' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Search Applicant</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Source Applicant')">
                            <router-link :to="{ name: 'client.applicant.source' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Source Applicant</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Resume Parser')">
                            <router-link :to="{ name: 'client.applicant.resume-parser' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Resume Parser</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="menu-item py-1" @mouseover="menuHover('process')" @mouseleave="menuHoverLeave('process')" :class="{ 'show menu-dropdown' : page.processHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-equalizer fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 304px);">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Processing</span>
                            </div>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicant Monitoring')">
                            <router-link :to="{ name: 'client.process.monitoring.applicants' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicant Monitoring</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Document Monitoring')">
                            <router-link :to="{ name: 'client.process.monitoring.documents' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Document Monitoring</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Document List')">
                            <router-link :to="{ name: 'client.process.monitoring.documentlist' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Document List</span>
                            </router-link>
                        </div>
                        <!-- <div class="menu-item">
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Medical</span>
                            </a>
                        </div> -->
                    </div>
                </div>
                <!-- <div class="menu-item py-1" @mouseover="menuHover('web')" @mouseleave="menuHoverLeave('web')" :class="{ 'show menu-dropdown' : page.webHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-layers fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 371px);">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Web Management</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Job Openings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Announcements</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Online Applicant</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Unposted Job</span>
                            </a>
                        </div>
                    </div>
                </div> -->
                <div class="menu-item py-1" @mouseover="menuHover('report')" @mouseleave="menuHoverLeave('report')" :class="{ 'show menu-dropdown' : page.reportHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-stats fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 380px);">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Reports</span>
                            </div>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicants Encoded')">
                            <router-link :to="{ name: 'client.reports.applicant.encoded' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicants Encoded</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicants Source')">
                            <router-link :to="{ name: 'client.reports.applicant.source' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicants Source</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Audit Trail')">
                            <router-link :to="{ name: 'client.reports.audit.trail' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Audit Trail</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Birthday')">
                            <router-link :to="{ name: 'client.reports.birthdate' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Birthday</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Deployment Report')">
                            <router-link :to="{ name: 'client.reports.deployment' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Deployment Report</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Interview Calendar')">
                            <router-link :to="{ name: 'client.reports.interview' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Interview Calendar</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Manpower Request')">
                            <router-link :to="{ name: 'client.reports.manpower-request' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Manpower Request</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Status Report')">
                            <router-link :to="{ name: 'client.reports.status' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Status Report</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="menu-item py-1" @mouseover="menuHover('settings')" @mouseleave="menuHoverLeave('settings')" :class="{ 'show menu-dropdown' : page.settingsHover}">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-setting fs-1"></i>
                        </span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown px-lg-2 py-lg-4 w-200px w-lg-225px show" style="z-index: 106; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(100px, 238px);"> <!-- 409px -->
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Settings</span>
                            </div>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Account Configuration')">
                            <router-link :to="{ name: 'client.settings.configuration' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Account Configuration</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicant Source')">
                            <router-link :to="{ name: 'client.settings.sources' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicant Source</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicant Status')">
                            <router-link :to="{ name: 'client.settings.status' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicant Status</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Applicant Trashbox')">
                            <router-link :to="{ name: 'client.settings.applicant' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Applicant Trashbox</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Clinic Manager')">
                            <router-link :to="{ name: 'client.settings.clinic' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Clinic Manager</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Document Type')">
                            <router-link :to="{ name: 'client.settings.document' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Document Type</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Email Template')">
                            <router-link :to="{ name: 'client.settings.email' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Email Template</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('Role Manager')">
                            <router-link :to="{ name: 'client.settings.role' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Role Manager</span>
                            </router-link>
                        </div>
                        <div class="menu-item" v-if="isCanRead('User Manager')">
                            <router-link :to="{ name: 'client.settings.user' }" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">User Manager</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <a href="#!" class="menu-item py-1" @click="logOut">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="fonticon-back fs-1"></i>
                        </span>
                    </span>
                </a>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Sidebar menu-->
    </div>
</template>

<script>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';

export default {
    setup() {
        const router = useRouter();
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            root_url: process.env.VUE_APP_ENDPOINT,
            dashboardHover: false,
            employerHover: false,
            applicantHover: false,
            processHover: false,
            webHover: false,
            reportHover: false,
            settingsHover: false,
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });

        const menuHover = (type) => {
            page.dashboardHover = (type == 'dashboard') ? true : false;
            page.employerHover = (type == 'employer') ? true : false;
            page.applicantHover = (type == 'applicant') ? true : false;
            page.processHover = (type == 'process') ? true : false;
            page.reportHover = (type == 'report') ? true : false;
            page.webHover = (type == 'web') ? true : false;
            page.settingsHover = (type == 'settings') ? true : false;
        }

        const menuHoverLeave = (type) => {
            page.dashboardHover = false;
            page.employerHover = false;
            page.applicantHover = false;
            page.processHover = false;
            page.webHover =  false;
            page.reportHover = false;
            page.settingsHover =  false;
        }

        const logOut = () => {
            localStorage.removeItem('authuser');
            localStorage.removeItem('applicant');
            window.location.href = '/auth/login';
        }

        const isCanRead = (name) => {
            if(page.authuser.role_id != 1) {
                let permissions = page.authuser.role?.permissions;
                let array_permission = false;
                permissions.forEach(item => {
                    if(item.name == name) {
                        array_permission = item.can_read == 1;
                    }
                });

                return array_permission;
            }

            return true;
        }

        return {
            page,
            menuHover,
            menuHoverLeave,
            logOut,
            isCanRead
        }
    },
}
</script>