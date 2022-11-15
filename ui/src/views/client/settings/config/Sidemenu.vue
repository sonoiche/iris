<template>
    <!--begin::Aside-->
    <div class="flex-column flex-md-row-auto w-100 w-lg-250px w-xxl-275px">
        <!--begin::Nav-->
        <div class="card mb-6 mb-xl-9">
            <!--begin::Card body-->
            <div class="card-body py-10 px-6">
                <!--begin::Menu-->
                <ul id="kt_account_settings" class="nav nav-flush menu menu-column menu-rounded menu-title-gray-600 menu-bullet-gray-300 menu-state-bg menu-state-bullet-primary fw-bold fs-6 mb-2">
                    <li class="menu-item px-3 pt-0 pb-1">
                        <a href="javascript:;" class="menu-link px-3 nav-link" :class="{ 'active' : activeComponent == 'Account' }" @click="openComponent('Account')">
                            <span class="menu-bullet">
                                <span class="bullet bullet-vertical"></span>
                            </span>
                            <span class="menu-title">Account Settings</span>
                        </a>
                    </li>
                    <li class="menu-item px-3 pt-0 pb-1">
                        <a href="javascript:;" class="menu-link px-3 nav-link" :class="{ 'active' : activeComponent == 'Agency' }" @click="openComponent('Agency')">
                            <span class="menu-bullet">
                                <span class="bullet bullet-vertical"></span>
                            </span>
                            <span class="menu-title">Agency Details</span>
                        </a>
                    </li>
                    <li class="menu-item px-3 pt-0 pb-1">
                        <a href="javascript:;" class="menu-link px-3 nav-link" :class="{ 'active' : activeComponent == 'Notification' }" @click="openComponent('Notification')">
                            <span class="menu-bullet">
                                <span class="bullet bullet-vertical"></span>
                            </span>
                            <span class="menu-title">Notifications</span>
                        </a>
                    </li>
                    <li class="menu-item px-3 pt-0 pb-1">
                        <a href="javascript:;" class="menu-link px-3 nav-link" :class="{ 'active' : activeComponent == 'Email' }" @click="openComponent('Email')">
                            <span class="menu-bullet">
                                <span class="bullet bullet-vertical"></span>
                            </span>
                            <span class="menu-title">Email Configuration</span>
                        </a>
                    </li>
                    <li class="menu-item px-3 pt-0">
                        <a href="javascript:;" class="menu-link px-3 nav-link" :class="{ 'active' : activeComponent == 'Manpower' }" @click="openComponent('Manpower')">
                            <span class="menu-bullet">
                                <span class="bullet bullet-vertical"></span>
                            </span>
                            <span class="menu-title">Manpower Request</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        activeComponent: {
            type: String,
            default: ''
        }
    },
    setup(props, {emit}) {
        const openComponent = (component) => {
            emit('open-component', component);
        }

        return {
            openComponent
        }
    },
}
</script>