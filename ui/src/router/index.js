import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import store from "@/store";

import settingsRoute from './settings/index';
import employerRoute from './employer/index';
import applicantRoute from './applicant/index';
import processRoute from './process/index';
import reportRoute from './reports/index';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import("../views/client/Dashboard.vue"),
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
    path: "/auth/register",
    name: "register",
    component: () => import("../views/client/auth/Register.vue"),
    beforeEnter: (to, from, next) => {
      if (store.getters["auth/authenticated"]) {
        return next({
          name: "client.dashboard"
        });
      }
      next();
    }
  },
  {
    path: "/auth/login",
    name: "login",
    component: () => import("../views/client/auth/Login.vue"),
    beforeEnter: (to, from, next) => {
      if (store.getters["auth/authenticated"]) {
        return next({
          name: "client.dashboard"
        });
      }
      next();
    }
  },
  {
    path: "/auth/password-reset",
    name: "password.reset",
    component: () => import("../views/client/auth/Password.vue"),
    beforeEnter: (to, from, next) => {
      if (store.getters["auth/authenticated"]) {
        return next({
          name: "client.dashboard"
        });
      }
      next();
    }
  },
  {
    path: "/client/dashboard",
    name: "client.dashboard",
    component: () => import("../views/client/Dashboard.vue"),
    beforeEnter: (to, from, next) => {
      if (!store.getters["auth/authenticated"]) {
        return next({
          name: "login"
        });
      }
      next();
    }
  },
  ...settingsRoute,
  ...employerRoute,
  ...applicantRoute,
  ...processRoute,
  ...reportRoute
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
