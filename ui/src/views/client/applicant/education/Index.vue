<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Manage Applicant Education</h3>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm" @click="addEducation">Add Education</button>
                            </div>
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
                                    <th class="fw-bolder">Education Level</th>
                                    <th class="fw-bolder">Education Field</th>
                                    <th class="fw-bolder">Course</th>
                                    <th class="fw-bolder">School</th>
                                    <th class="fw-bolder">Location</th>
                                    <th class="fw-bolder text-center">School Year</th>
                                    <th class="fw-bolder text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="educations.length">
                                <tr v-for="(education, index) in educations" :key="education">
                                    <td class="text-center align-middle">{{ index+1 }}</td>
                                    <td class="align-middle">{{ education.education_level_name }}</td>
                                    <td class="align-middle">{{ education.education_field?.name }}</td>
                                    <td class="align-middle">{{ education.course }}</td>
                                    <td class="align-middle">{{ education.school }}</td>
                                    <td class="align-middle">{{ education.location }}</td>
                                    <td class="text-center align-middle">{{ education.school_year }}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown" aria-expanded="false">Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                        <div class="dropdown-menu menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" class="menu-link px-3" @click="editEducation(education.id)">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" class="menu-link px-3" @click="deleteEducation(education.id)">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="8" class="text-center">No records found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, inject } from 'vue';
import { useRoute } from 'vue-router';
import educationRepo from '@/repositories/applicants/education';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const swal = inject('$swal');
        const state = reactive({
            isLoading: true
        });
        const { status, educations, getEducations, destroyEducation } = educationRepo();

        const addEducation = () => {
            emit('add-data', 'ApplicantCreateEducation');
        }

        const editEducation = (id) => {
            emit('add-data', 'ApplicantEditEducation', id);
        }

        const deleteEducation = (id) => {
            swal({
                title: 'Are you sure?',
                text: "You want to delete this education?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    state.isLoading = true;
                    await destroyEducation(id);
                    if(status.value == 200) {
                        getEducations(route.params.id);
                        state.isLoading = false;
                    }
                }
            });
        }

        onMounted( async () => {
            await getEducations(route.params.id);
            state.isLoading = false;
        });

        return {
            state,
            status,
            educations,
            getEducations,
            destroyEducation,
            addEducation,
            editEducation,
            deleteEducation,
        }
    },
}
</script>