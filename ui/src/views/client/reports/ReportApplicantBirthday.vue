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
                                                    <h3 class="fw-bolder m-0">Applicants Birthday</h3>
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
                                                            label="Status"
                                                            :placeholder="`All Status`"
                                                            :id="`status`"
                                                            :options="statusOptions"
                                                            @select-value="setStatus"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                                            <label class="form-label fs-6 fw-bolder mb-3">Birthdate</label>
                                                            <date-picker 
                                                                v-model="state.date"
                                                                format="MMMM"
                                                                inputClassName="form-control form-control-solid fc-calendar"
                                                                month-picker
                                                                :timezone="`Asia/Dhaka`"
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
import statusRepo from '@/repositories/settings/status';
import { useRouter } from 'vue-router';

export default {
    setup(props) {
        const router = useRouter();
        const { results, getStatuses } = statusRepo();
        const state = reactive({
            status_id: '',
            date: ''
        });

        const statusOptions = computed(() => {
            const arr_status = [];
            results.value.forEach(item => {
                arr_status.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_status;
        });

        const setStatus = (value) => {
            state.status_id = value.id;
        }

        const generateReport = () => {
            const form = {
                status_id: state.status_id,
                birthmonth: (state.date) ? JSON.stringify(state.date) : ''
            }

            localStorage.setItem('report-birthday', JSON.stringify(form));
            const routeData = router.resolve({ name: 'client.reports.birthday.lists' });
            window.open(routeData.href, '_blank');
        }

        onMounted(() => {
            const endDate = new Date();
            const startDate = new Date(new Date().setDate(endDate.getDate() - 7));
            state.date = [startDate, endDate];

            getStatuses(); 
        });

        return {
            state,
            results,
            getStatuses,
            statusOptions,
            setStatus,
            generateReport
        }
    }
}
</script>