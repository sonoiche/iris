<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container container-xxl">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title w-full">
                                            <div class="d-flex justify-content-between w-full">
                                                <div class="d-flex align-items-center">
                                                    <h3 class="fw-bolder m-0">Manpower Request Report</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                                <div class="row mb-6">
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Principal"
                                                            :options="principals"
                                                            :placeholder="`All Principal`"
                                                            id="principal_id"
                                                            @select-value="setPrincipal"
                                                            @remove-value="removePrincipal"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <BaseSelect
                                                            label="Manpower Request"
                                                            :options="joborderOptions"
                                                            :placeholder="`All Manpower Request`"
                                                            id="job_order_id"
                                                            @select-value="setJobOrder"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                                            <label class="form-label fs-6 fw-bolder mb-3">Date Created</label>
                                                            <date-picker 
                                                                v-model="state.date"
                                                                format="MM/dd/yyyy"
                                                                inputClassName="form-control form-control-solid fc-calendar"
                                                                range multi-calendars
                                                            ></date-picker>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-6">
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-primary" @click="generateReport">Create</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, reactive, onMounted } from 'vue';
import principalRepo from '@/repositories/employer/principal';
import joborderRepo from '@/repositories/employer/joborder';
import { useRouter } from 'vue-router';

export default {
    setup(props) {
        const router = useRouter();
        const { principals, getSelectPrincipal } = principalRepo();
        const { joborders, getJobOrdersByPrincipal } = joborderRepo();
        const state = reactive({
            principal_id: '',
            job_order_id: '',
            date: ''
        });

        const joborderOptions = computed(() => {
            const arr_joborders = [];
            joborders.value.forEach(item => {
                arr_joborders.push({
                    id: item.position_id,
                    name: `${item.job_order_number} - ${item.position_title}`
                });
            });

            return arr_joborders;
        });

        const setPrincipal = async (value) => {
            state.principal_id = value.id;
            await getJobOrdersByPrincipal(value.id);
        }

        const setJobOrder = (value) => {
            state.job_order_id = value.id;
        }

        const removePrincipal = () => {
            state.principal_id = 0;
        }

        const generateReport = () => {
            const form = {
                principal_id: state.principal_id,
                job_order_id: state.job_order_id,
                from: (state.date) ? new Date(state.date[0]).toISOString() : '',
                to: (state.date) ? new Date(state.date[1]).toISOString() : ''
            }

            localStorage.setItem('report-manpower', JSON.stringify(form));
            const routeData = router.resolve({ name: 'client.reports.manpower.list' });
            window.open(routeData.href, '_blank');
        }

        onMounted(() => {
            const endDate = new Date();
            const startDate = new Date(new Date().setDate(endDate.getDate() - 7));
            state.date = [startDate, endDate];
        });

        return {
            state,
            principals,
            getSelectPrincipal,
            joborders,
            getJobOrdersByPrincipal,
            joborderOptions,
            setPrincipal,
            setJobOrder,
            removePrincipal,
            generateReport
        }
    }
}
</script>