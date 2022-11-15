<template>
    <div id="kt_app_header" class="app-header" :class="{ 'iris-header' : page.routename !== 'client.dashboard' }">
        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
                <div class="btn btn-icon btn-active-color-primary w-35px h-35px">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
                            <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                <a href="#" class="d-lg-none">
                    <img alt="Logo" :src="`${page.base_url}/assets/media/logos/default.svg`" class="h-30px" />
                </a>
            </div>
            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}" class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bolder fs-3 flex-column justify-content-center my-0">{{ title }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to="{ name: 'client.dashboard' }" class="breadcrumb-link text-muted text-hover-primary">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">User Management</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Roles</li>
                    </ul>
                </div>
                <div class="app-navbar align-items-center flex-shrink-0">
                    <div class="app-navbar-item ms-2 ms-lg-4">
                        <div id="kt_header_search" class="header-search d-flex align-items-center w-lg-250px" data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu" data-kt-search-responsive="lg" data-kt-menu-trigger="auto" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end" data-kt-menu-attach="parent">
                            <div data-kt-search-element="toggle" class="d-flex d-lg-none align-items-center">
                                <div class="btn btn-custom btn-outline btn-icon btn-icon-gray-700 btn-active-icon-primary">
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <form data-kt-search-element="form" class="d-none d-lg-block w-100 position-relative mb-5 mb-lg-0" autocomplete="off">
                                <span class="svg-icon svg-icon-2 svg-icon-lg-3 svg-icon-gray-800 position-absolute top-50 translate-middle-y ms-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M21.7 18.9L18.6 15.8C17.9 16.9 16.9 17.9 15.8 18.6L18.9 21.7C19.3 22.1 19.9 22.1 20.3 21.7L21.7 20.3C22.1 19.9 22.1 19.3 21.7 18.9Z" fill="currentColor" />
                                        <path opacity="0.3" d="M11 20C6 20 2 16 2 11C2 6 6 2 11 2C16 2 20 6 20 11C20 16 16 20 11 20ZM11 4C7.1 4 4 7.1 4 11C4 14.9 7.1 18 11 18C14.9 18 18 14.9 18 11C18 7.1 14.9 4 11 4ZM8 11C8 9.3 9.3 8 11 8C11.6 8 12 7.6 12 7C12 6.4 11.6 6 11 6C8.2 6 6 8.2 6 11C6 11.6 6.4 12 7 12C7.6 12 8 11.6 8 11Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" class="search-input form-control form-control-solid ps-13" name="search" value="" placeholder="Search..." data-kt-search-element="input" />
                                <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5" data-kt-search-element="spinner">
                                    <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                                </span>
                                <span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4" data-kt-search-element="clear">
                                    <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                            </form>
                        </div>
                    </div>
                    <div class="app-navbar-item ms-2 ms-lg-4"></div>
                    <div class="app-navbar-item ms-2 ms-lg-4">
                        <a href="#" id="kt_drawer_chat_toggle" class="btn btn-custom btn-outline btn-icon btn-icon-gray-700 btn-active-icon-primary" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z" fill="currentColor" />
                                    <rect x="6" y="12" width="7" height="2" rx="1" fill="currentColor" />
                                    <rect x="6" y="7" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                        </a>
                    </div>
                    <div class="app-navbar-item ms-2 ms-lg-4"></div>
                    <div class="app-navbar-item ms-2 ms-lg-4"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';

export default {
    props: {
        title: {
            type: String,
            default: ''
        }
    },
    setup() {
        const route = useRoute();
        const page = reactive({
            base_url: process.env.VUE_APP_URL,
            routename: ''
        });

        onMounted(() => {
            page.routename = route.name;
        });

        return {
            page
        }
    },
}
</script>

<style>
.iris-header {
    background-color: #161514 !important;
    padding-bottom: 60px;
    height: 240px !important;
}
.iris-app-main {
    margin-top: -100px !important;
    min-height: 80vh;
}
[data-kt-app-layout=mini-sidebar] .card:not(.bg-gray-200) {
    background-color: #ffffff;
    border: 1px dashed #E8E5DD;
}
[data-kt-app-layout=mini-sidebar] .page-title .page-heading {
    color: #D5D1CE !important;
    font-size: 1.85rem !important;
}
@media (min-width: 1400px) {
    .container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container {
        max-width: 1480px;
    }
}
</style>