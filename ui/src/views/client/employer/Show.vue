<template>
    <div>
        <Header :title="`Employer Manager`" />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 80%">
                        <div class="card mb-5 mb-xl-12">
                            <div class="card-header border-0 d-flex justify-content-between">
                                <div class="card-title">
                                    <loading v-if="isLoading" />
                                    <h3 class="fw-bolder m-0" v-else>{{ principal.name }}</h3>
                                </div>
                                <div class="d-flex align-items-center">
                                    <router-link class="btn btn-primary btn-sm" :to="{ name: 'client.employer.edit', params: { id: principal_id } }">Edit</router-link>
                                </div>
                            </div>
                            <div class="card-body border-top p-9">
                                <loading v-if="isLoading" />
                                <div v-else>
                                    <custom-row label="Code" :value="principal.code" />
                                    <custom-row label="Address" :value="principal.address" />
                                    <custom-row label="Website" :value="principal.website" />
                                    <custom-row label="Landline Number" :value="principal.landline" />
                                    <custom-row label="Mobile Number" :value="principal.mobile_number" />
                                    <custom-row label="Accreditation Number" :value="principal.accreditation_number" />
                                    <custom-row label="Issued" :value="principal.date_issue_display" />
                                    <custom-row label="Expiry" :value="principal.date_expiry_display" />
                                    <custom-row label="Industry" :value="principal.industry?.name" />
                                    <custom-row label="Remarks" :value="principal.remarks" />
                                </div>
                            </div>
                        </div>
                        <component :is="activeContactComponent" :principal_id="principal_id"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import principalRepo from '@/repositories/employer/principal';
import ActiveContact from '@/views/client/employer/components/ActiveContact.vue';

export default {
    setup() {
        const route = useRoute();
        const { principal, getPrincipal } = principalRepo();
        const principal_id = ref(route.params.id);
        const isLoading = ref(true);
        const activeContactComponent = ref('ActiveContact');

        onMounted( async () => {
            await getPrincipal(principal_id.value);
            isLoading.value = false;
        });

        return {
            principal,
            principal_id,
            getPrincipal,
            isLoading,
            activeContactComponent
        }
    },
    components: {
        ActiveContact
    }
}
</script>