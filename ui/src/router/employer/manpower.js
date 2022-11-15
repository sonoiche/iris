import store from "@/store";
import ManpowerRequestIndex from '@/views/client/manpower/Index.vue';
import ManpowerRequestCreate from '@/views/client/manpower/Create.vue';
import ManpowerRequestEdit from '@/views/client/manpower/Edit.vue';
import ManpowerRequestOwned from '@/views/client/manpower/Owned.vue';

const routes = [
    {
        path: '/client/job-order',
        name: 'client.joborder',
        component: ManpowerRequestIndex,
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
        path: '/client/job-order/create',
        name: 'client.joborder.create',
        component: ManpowerRequestCreate,
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
        path: '/client/job-order/:id/edit',
        name: 'client.joborder.edit',
        component: ManpowerRequestEdit,
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
        path: '/client/job-order/owned',
        name: 'client.joborder.owned',
        component: ManpowerRequestOwned,
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