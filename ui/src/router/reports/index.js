import store from "@/store";
import ReportApplicantEncoded from '@/views/client/reports/ApplicantEncoded.vue';
import ReportApplicantEncodedList from '@/views/client/reports/components/ReportApplicantEncodedList.vue';
import ReportApplicantSource from '@/views/client/reports/ReportApplicantSource.vue';
import ReportApplicantSourceList from '@/views/client/reports/components/ReportApplicantSourceList.vue';
import ReportApplicantSourceApplicants from '@/views/client/reports/components/ReportApplicantSourceApplicants.vue';
import ReportAuditTrail from '@/views/client/reports/ReportAuditTrail.vue';
import ReportAuditTrailList from '@/views/client/reports/components/ReportAuditTrailList.vue';
import ReportApplicantBirthday from '@/views/client/reports/ReportApplicantBirthday.vue';
import ReportApplicantBirthdayList from '@/views/client/reports/components/ReportApplicantBirthdayList.vue';
import ReportApplicantDeployment from '@/views/client/reports/ReportApplicantDeployment.vue';
import ReportApplicantDeploymentList from '@/views/client/reports/components/ReportApplicantDeploymentList.vue';
import ReportApplicantInterview from '@/views/client/reports/ReportApplicantInterview.vue';
import ReportApplicantInterviewList from '@/views/client/reports/components/ReportApplicantInterviewList.vue';
import ReportManpowerRequest from '@/views/client/reports/ReportManpowerRequest.vue';
import ReportManpowerRequestList from '@/views/client/reports/components/ReportManpowerRequestList.vue';
import ReportApplicantStatus from '@/views/client/reports/ReportApplicantStatus.vue';
import ReportApplicantStatusList from '@/views/client/reports/components/ReportApplicantStatusList.vue';
import ReportApplicantStatusApplicants from '@/views/client/reports/components/ReportApplicantStatusApplicants.vue';

const routes = [
    {
        path: '/client/reports/applicant/encoded',
        name: 'client.reports.applicant.encoded',
        component: ReportApplicantEncoded,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant/encoded-lists',
        name: 'client.reports.applicant.encoded.list',
        component: ReportApplicantEncodedList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant/source',
        name: 'client.reports.applicant.source',
        component: ReportApplicantSource,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant/source-lists',
        name: 'client.reports.applicant.source.lists',
        component: ReportApplicantSourceList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant/source-applicants/:id',
        name: 'client.reports.applicant.source.applicants',
        component: ReportApplicantSourceApplicants,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/audit-trail',
        name: 'client.reports.audit.trail',
        component: ReportAuditTrail,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/audit-trail/list',
        name: 'client.reports.audit-trail.list',
        component: ReportAuditTrailList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/birthday',
        name: 'client.reports.birthdate',
        component: ReportApplicantBirthday,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/birthday/lists',
        name: 'client.reports.birthday.lists',
        component: ReportApplicantBirthdayList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/deployment',
        name: 'client.reports.deployment',
        component: ReportApplicantDeployment,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/deployment/list',
        name: 'client.reports.deployment.list',
        component: ReportApplicantDeploymentList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/interview',
        name: 'client.reports.interview',
        component: ReportApplicantInterview,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/interview/list',
        name: 'client.reports.interview.list',
        component: ReportApplicantInterviewList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/manpower-request',
        name: 'client.reports.manpower-request',
        component: ReportManpowerRequest,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/manpower-request/list',
        name: 'client.reports.manpower.list',
        component: ReportManpowerRequestList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant-status',
        name: 'client.reports.status',
        component: ReportApplicantStatus,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant-status/list',
        name: 'client.reports.status.list',
        component: ReportApplicantStatusList,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    },
    {
        path: '/client/reports/applicant/status-applicants/:id',
        name: 'client.reports.applicant.status.applicants',
        component: ReportApplicantStatusApplicants,
        beforeEnter: (to, from, next) => {
            if (!store.getters["auth/authenticated"]) {
                return next({
                    name: "login"
                });
            }
            next();
        }
    }
]

export default routes