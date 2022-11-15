import store from "@/store";
import ApplicantCreate from '@/views/client/applicant/Create.vue';
import ApplicantShow from '@/views/client/applicant/Show.vue';
import ApplicantEdit from '@/views/client/applicant/Edit.vue';
import ApplicantPipeline from '@/views/client/applicant/pipeline/Index.vue';
import ApplicantShowPipeline from '@/views/client/applicant/pipeline/Show.vue';
import InterviewIndex from '@/views/client/applicant/interview/Index.vue';
import InterviewCreate from '@/views/client/applicant/interview/Create.vue';

const routes = [
    {
        path: '/client/applicant/create',
        name: 'client.applicant.create',
        component: ApplicantCreate,
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
        path: '/client/applicant/:id',
        name: 'client.applicant.show',
        component: ApplicantShow,
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
        path: '/client/applicant/:id/edit',
        name: 'client.applicant.edit',
        component: ApplicantEdit,
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
        path: '/client/applicant/pipeline',
        name: 'client.applicant.pipeline',
        component: ApplicantPipeline,
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
        path: '/client/applicant/pipeline/:status_id',
        name: 'client.applicant.pipeline.show',
        component: ApplicantShowPipeline,
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
        path: '/client/applicant/interview',
        name: 'client.interview',
        component: InterviewIndex,
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
        path: '/client/applicant/interview/create',
        name: 'client.interview.create',
        component: InterviewCreate,
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