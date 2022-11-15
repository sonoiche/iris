<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 90%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title d-flex justify-content-between w-full">
                                            <h3 class="fw-bolder m-0">Update Applicant Pipeline</h3>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-primary" @click="updateLineup" :disabled="state.disabled">Change Status</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <loading v-if="state.isLoading" />
                                        <div class="card-body border-top p-9" v-else>
                                            <table class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th class="fw-bolder text-center">
                                                            <input class="form-check-input form-chk" type="checkbox" v-model="state.checkAll" @change="checkAll" v-if="lineups.length" />
                                                        </th>
                                                        <th class="fw-bolder">Applicant Name</th>
                                                        <th class="fw-bolder text-center">Age / Gender</th>
                                                        <th class="fw-bolder">Latest Position</th>
                                                        <th class="fw-bolder">Course</th>
                                                        <th class="fw-bolder">Date Added</th>
                                                        <th class="fw-bolder">Mobile Number</th>
                                                        <th class="fw-bolder text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="lineups.length">
                                                    <tr v-for="lineup in lineups" :key="lineup.id">
                                                        <td class="text-center">
                                                            <input class="form-check-input form-chk" type="checkbox" v-model="state.applicant_ids" :value="lineup.applicant_id" :id="`chk-${lineup.applicant_id}`" />
                                                        </td>
                                                        <td><label :for="`chk-${lineup.applicant_id}`">{{ lineup.applicant?.fullname }}</label></td>
                                                        <td class="text-center">{{ lineup.applicant?.age_gender }}</td>
                                                        <td>{{ lineup.position?.position_title }}</td>
                                                        <td>{{ lineup.applicant?.educations[0]?.course ?? '' }}</td>
                                                        <td>{{ lineup.created_at_display }}</td>
                                                        <td>{{ lineup.applicant?.mobile_number }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-danger btn-xs" @click="deleteLineup(lineup.id)">Delete</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No data available</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ModalUpdateLineup :is-active="modalActive" :state="state" :isLoading="state.dataLoading" @close-modal="closeModal" @refresh-table="refresh" />
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch, inject } from 'vue';
import { useRoute } from 'vue-router';
import lineupRepo from '@/repositories/applicants/lineup';
import ModalUpdateLineup from '@/views/client/applicant/pipeline/modals/Update.vue';

export default {
    components: {
        ModalUpdateLineup
    },
    setup() {
        const swal = inject('$swal');
        const route = useRoute();
        const state = reactive({
            isLoading: true,
            lineup: {
                status_id: route.params.status_id,
                position_id: route.query.position_id
            },
            applicant_ids: [],
            checkAll: false,
            disabled: true,
            dataLoading: false
        });
        const modalActive = ref(false);
        const { lineups, getLineupByStatus, status, destroyLineup } = lineupRepo();

        const updateLineup = () => {
            modalActive.value = true;
        }

        const closeModal = () => {
            modalActive.value = false;
        }

        const refresh = async () => {
            state.dataLoading = false;
            state.disabled = true;
            state.checkAll = false;
            await getLineupByStatus(state.lineup);
        }

        const checkAll = () => {
            if(state.checkAll) {
                lineups.value.forEach(item => {
                    state.applicant_ids.push(item.applicant_id);
                });
            } else {
                state.applicant_ids = [];
            }
            state.disabled = (state.applicant_ids.length == 0);
        }

        const deleteLineup = (id) => {
            swal({
                title: 'Are you sure?',
                text: "You want to delete this?",
                icon: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, please'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    await destroyLineup(id);
                    if(status.value == 200) {
                        await getLineupByStatus(state.lineup);
                    }
                }
            });
        }

        onMounted( async () => {
            await getLineupByStatus(state.lineup);
            setTimeout(() => {
                state.isLoading = false;
            }, 800);
        });

        watch(() => state.applicant_ids, () => {
            state.checkAll = (state.applicant_ids.length == lineups.value.length);
            state.disabled = (state.applicant_ids.length == 0);
        });

        return {
            state,
            modalActive,
            updateLineup,
            closeModal,
            refresh,
            lineups,
            getLineupByStatus,
            checkAll,
            status,
            deleteLineup
        }
    },
}
</script>

<style>
.btn-xs {
    padding: 3px 10px !important;
}
.form-chk {
    width: 15px !important;
    height: 15px !important;
    margin-top: 0 !important;
    border-radius: 3px !important;
}
.w-full {
    width: 100%;
}
</style>