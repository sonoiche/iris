<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <div class="d-flex flex-column flex-lg-row">
                            <Sidemenu :active-component="activeComponent" @open-component="openComponent" />
                            <component :is="activeComponent"></component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref } from 'vue';
import Sidemenu from '@/views/client/settings/config/Sidemenu.vue';
import Account from '@/views/client/settings/config/components/Account.vue';
import Agency from '@/views/client/settings/config/components/Agency.vue';
import Notification from '@/views/client/settings/config/components/Notification.vue';
import Email from '@/views/client/settings/config/components/Email.vue';
import Manpower from '@/views/client/settings/config/components/Manpower.vue';

export default {
    setup() {
        const page = reactive({
            base_url: process.env.VUE_APP_URL
        });

        const activeComponent = ref('Account');
        const openComponent = (component) => {
            activeComponent.value = component;
        }

        return {
            page,
            activeComponent,
            openComponent
        }
    },
    components: {
        Sidemenu,
        Account,
        Agency,
        Notification,
        Email,
        Manpower
    }
}
</script>