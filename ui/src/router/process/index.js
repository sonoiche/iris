import store from "@/store";
import ApplicantMonitoring from '@/views/client/process/Applicant.vue';
import DocumentMonitoring from '@/views/client/process/Document.vue';
import DocumentList from '@/views/client/process/DocumentList.vue';

const routes = [
    {
        path: '/client/process/monitoring/applicants',
        name: 'client.process.monitoring.applicants',
        component: ApplicantMonitoring,
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
        path: '/client/process/monitoring/documents',
        name: 'client.process.monitoring.documents',
        component: DocumentMonitoring,
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
        path: '/client/process/monitoring/documentlist',
        name: 'client.process.monitoring.documentlist',
        component: DocumentList,
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