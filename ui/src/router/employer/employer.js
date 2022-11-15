import store from "@/store";
import EmployerIndex from '@/views/client/employer/Index.vue';
import EmployerCreate from '@/views/client/employer/Create.vue';
import EmployerEdit from '@/views/client/employer/Edit.vue';
import EmployerShow from '@/views/client/employer/Show.vue';

const routes = [
    {
        path: '/client/employer',
        name: 'client.employer',
        component: EmployerIndex,
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
        path: '/client/employer/create',
        name: 'client.employer.create',
        component: EmployerCreate,
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
        path: '/client/employer/:id/edit',
        name: 'client.employer.edit',
        component: EmployerEdit,
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
        path: '/client/employer/:id',
        name: 'client.employer.show',
        component: EmployerShow,
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