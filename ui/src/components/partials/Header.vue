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
                    <img alt="Logo" :src="`${page.base_url}/assets/media/logos/logo.png`" class="h-30px" />
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
                        <!-- <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">User Management</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-300 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Roles</li> -->
                    </ul>
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