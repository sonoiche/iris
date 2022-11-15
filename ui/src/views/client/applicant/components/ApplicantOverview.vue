<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Personal Information</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Applicant :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Education</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Education :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Employment</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Employment :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">License and Certifications</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <License :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Skills and Strengths</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Skill :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Trainings & Seminars</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Training :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Character References</h3>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body border-top p-9">
                        <Reference :applicant_id="applicant_id" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import Applicant from '@/views/client/applicant/components/Applicant.vue';
import Education from '@/views/client/applicant/components/Education.vue';
import Employment from '@/views/client/applicant/components/Employment.vue';
import License from '@/views/client/applicant/components/License.vue';
import Skill from '@/views/client/applicant/components/Skill.vue';
import Training from '@/views/client/applicant/components/Training.vue';
import Reference from '@/views/client/applicant/components/Reference.vue';

export default {
    setup() {
        const route = useRoute();
        const applicant_id = ref(route.params.id);

        return {
            applicant_id
        }
    },
    components: {
        Applicant,
        Education,
        Employment,
        License,
        Skill,
        Training,
        Reference
    }
}
</script>