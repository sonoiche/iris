<template>
    <div class="app-container mx-auto" style="width: 90%">
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-md-row-fluid ms-lg-12">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0">
                        <div class="card-title d-flex justify-content-between w-full">
                            <h3 class="fw-bolder m-0">Applicant Source Results</h3>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary" @click="resetApplicantSource">Applicant Source</button>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show">
                        <loading v-if="state.isLoading" />
                        <div class="card-body border-top p-9" v-else>
                            <table class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th class="fw-bolder text-center">#</th>
                                        <th class="fw-bolder">Full Name</th>
                                        <th class="fw-bolder">Email</th>
                                        <th class="fw-bolder text-center">Age/Gender</th>
                                        <th class="fw-bolder">Height</th>
                                        <th class="fw-bolder">Weight</th>
                                        <th class="fw-bolder">Contacts</th>
                                        <th class="fw-bolder">Latest Employment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(applicant, index) in applicants" :key="applicant.id">
                                        <td class="text-center">{{ index+1 }}</td>
                                        <td><div v-html="applicant.applicant_name"></div></td>
                                        <td>{{ applicant.email }}</td>
                                        <td class="text-center">{{ applicant.age_gender }}</td>
                                        <td>{{ applicant.height }}</td>
                                        <td>{{ applicant.weight }}</td>
                                        <td>{{ applicant.mobile_number }}</td>
                                        <td>{{ applicant.latest_employment }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive } from 'vue';

export default {
    props: {
        applicants: {
            type: Array,
            default: []
        }
    },
    setup(props, {emit}) {
        const state = reactive({
            isLoading: false
        });

        const resetApplicantSource = () => {
            emit('reset-page');
        }

        return {
            state,
            resetApplicantSource
        }
    }
}
</script>