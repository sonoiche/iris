<template>
    <div>
        <div v-if="!page.authuser" id="kt_app_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" data-bs-offset="200" data-kt-app-layout="light-sidebar" class="body-bg position-relative app-blank">
            <router-view />
        </div>
        <div v-else class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <Sidebar />
                <div class="layout-page">
                    <router-view />
                    <Footer />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/components/partials/Sidebar.vue';
import Footer from '@/components/partials/Footer.vue';
import { reactive } from '@vue/reactivity';

export default {
    setup() {
        const page = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser'))
        });

        return {
            page
        }
    },
    components: {
        Sidebar,
        Footer
    }
}
</script>