<template>
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-md-row-fluid ms-lg-12">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title w-100">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bolder m-0">Applicant Lineup</h3>
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
                                    <th class="fw-bolder">Principal</th>
                                    <th class="fw-bolder">Manpower Request</th>
                                    <th class="fw-bolder">Position</th>
                                    <th class="fw-bolder">Date</th>
                                    <th class="fw-bolder">Lineup Status</th>
                                    <th class="fw-bolder">User</th>
                                    <th class="fw-bolder">Remarks</th>
                                </tr>
                            </thead>
                            <tbody v-if="lineups.length">
                                <tr v-for="(lineup, index) in lineups" :key="lineup">
                                    <td class="text-center align-middle">{{ index+1 }}</td>
                                    <td class="align-middle">{{ lineup.employer?.name }}</td>
                                    <td class="align-middle">{{ lineup.job_order?.job_order_number }}</td>
                                    <td class="align-middle">{{ lineup.position?.position_title }}</td>
                                    <td class="align-middle">{{ lineup.created_at_display }}</td>
                                    <td class="align-middle">{{ lineup.lineup_status?.name }}</td>
                                    <td class="align-middle">{{ lineup.user?.fullname }}</td>
                                    <td class="align-middle">{{ lineup.remarks }}</td>
                                    <td class="align-middle"></td>
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
import { onMounted, reactive } from 'vue';
import { useRoute } from 'vue-router';
import lineupRepo from '@/repositories/applicants/lineup';

export default {
    setup(props, {emit}) {
        const route = useRoute();
        const state = reactive({
            isLoading: true
        });
        const { status, lineups, getLineups } = lineupRepo();

        onMounted( async () => {
            await getLineups(route.params.id);
            state.isLoading = false;
        });

        return {
            state,
            status,
            lineups,
            getLineups
        }
    },
}
</script>