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
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Applicant Pipeline</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <loading v-if="state.isLoading" />
                                        <div class="card-body border-top p-9" v-else>
                                            <table class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th class="fw-bolder">MR. No / Position</th>
                                                        <th class="fw-bolder text-center" v-for="result in results" :key="result.id">{{ result.name }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="joborder in joborders" :key="joborder.id">
                                                        <td class="gothic">{{ `${joborder.job_order_number} - ${joborder.position_title}` }}</td>
                                                        <td v-for="result in joborder.arr_status" :key="result.status" class="text-center">
                                                            <a href="javascript:;" @click="addLineup(result.status_id, joborder.position_id)" v-if="result.count == 0"><b>{{ result.count }}</b></a>
                                                            <a href="javascript:;" @click="updateLineup(result.status_id, joborder.position_id)" v-else><b>{{ result.count }}</b></a>
                                                        </td>
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
        <ModalLineup :is-active="modalActive" :lineup="state.lineup" :isLoading="state.dataLoading" @close-modal="closeModal" @refresh-table="refresh" />
    </div>
</template>

<script>
import { reactive, ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import { useRouter } from 'vue-router';
import statusRepo from '@/repositories/settings/status';
import joborderRepo from '@/repositories/employer/joborder';
import ModalLineup from '@/views/client/applicant/pipeline/modals/Index.vue';

export default {
    components: {
        ModalLineup
    },
    setup() {
        const router = useRouter();
        const { results, getStatuses } = statusRepo();
        const { joborders, getJobOrderPositions, getLineUpCount } = joborderRepo();
        const state = reactive({
            authuser: JSON.parse(localStorage.getItem('authuser')),
            isLoading: true,
            lineup: {
                status_id: null,
                position_id: null
            },
            dataLoading: true
        });
        const modalActive = ref(false);

        const addLineup = (id, position_id) => {
            state.lineup.status_id = id;
            state.lineup.position_id = position_id;
            modalActive.value = true;
            setTimeout(() => {
                state.dataLoading = false;
            }, 500);
        }

        const updateLineup = (id, position_id) => {
            router.push({
                name: 'client.applicant.pipeline.show',
                params: {
                    status_id: id
                },
                query: {
                    position_id: position_id
                }
            });
        }

        const closeModal = () => {
            modalActive.value = false;
        }

        const refresh = () => {
            getStatuses();
            getJobOrderPositions();
            state.dataLoading = false;
        }

        onMounted(() => {
            getStatuses();
            getJobOrderPositions();
            setTimeout(() => {
                state.isLoading = false;
            }, 800);
        });

        return {
            state,
            results,
            getStatuses,
            joborders,
            getJobOrderPositions,
            getLineUpCount,
            addLineup,
            updateLineup,
            closeModal,
            refresh,
            modalActive
        }
    },
}
</script>

<style>
.gothic {
    font-family: Century Gothic;
    letter-spacing: 1px;
}
</style>