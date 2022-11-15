import ConfigIndex from '@/views/client/settings/config/Index.vue';
import SourceIndex from '@/views/client/settings/sources/Index.vue';
import StatusIndex from '@/views/client/settings/status/Index.vue';
import ClinicIndex from '@/views/client/settings/clinic/Index.vue';
import DocumentIndex from '@/views/client/settings/document/Index.vue';
import EmailIndex from '@/views/client/settings/email/Index.vue';
import RoleIndex from '@/views/client/settings/role/Index.vue';
import RoleShow from '@/views/client/settings/role/Show.vue';
import UserIndex from '@/views/client/settings/user/Index.vue';

import store from "@/store";

const routes = [
    {
        path: '/client/settings/configuration',
        name: 'client.settings.configuration',
        component: ConfigIndex,
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
        path: '/client/settings/sources',
        name: 'client.settings.sources',
        component: SourceIndex,
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
        path: '/client/settings/status',
        name: 'client.settings.status',
        component: StatusIndex,
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
        path: '/client/settings/clinic',
        name: 'client.settings.clinic',
        component: ClinicIndex,
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
        path: '/client/settings/document',
        name: 'client.settings.document',
        component: DocumentIndex,
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
        path: '/client/settings/email',
        name: 'client.settings.email',
        component: EmailIndex,
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
        path: '/client/settings/role',
        name: 'client.settings.role',
        component: RoleIndex,
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
        path: '/client/settings/role/:id',
        name: 'client.settings.role-show',
        component: RoleShow,
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
        path: '/client/settings/user',
        name: 'client.settings.user',
        component: UserIndex,
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