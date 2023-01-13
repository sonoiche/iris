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
                                                    <h3 class="fw-bolder m-0">Applicants Encoded</h3>
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
                                                            label="Encoded By"
                                                            :options="userOptions"
                                                            :placeholder="`All Users`"
                                                            id="user_id"
                                                            @select-value="setUser"
                                                        />
                                                    </div>
                                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                                        <div class="fv-row mb-0 fv-plugins-icon-container">
                                                            <label class="form-label fs-6 fw-bolder mb-3">Date</label>
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
import userRepo from '@/repositories/settings/users';
import { useRouter } from 'vue-router';

export default {
    setup(props) {
        const router = useRouter();
        const { users, getUsers } = userRepo();
        const state = reactive({
            user_id: '',
            date: ''
        });

        const userOptions = computed(() => {
            const arr_users = [];
            users.value.forEach(item => {
                arr_users.push({
                    id: item.id,
                    name: item.fullname
                });
            });

            return arr_users;
        });

        const setUser = (value) => {
            state.user_id = value.id;
        }

        const generateReport = () => {
            const form = {
                user_id: state.user_id,
                from: (state.date) ? new Date(state.date[0]).toISOString() : '',
                to: (state.date) ? new Date(state.date[1]).toISOString() : ''
            }

            // localStorage.setItem('encoded-applicants', JSON.stringify(form));
            const routeData = router.resolve({ name: 'client.reports.applicant.encoded.list', query: form });
            window.open(routeData.href, '_blank');
        }

        onMounted(() => {
            const endDate = new Date();
            const startDate = new Date(new Date().setDate(endDate.getDate() - 7));
            state.date = [startDate, endDate];

            getUsers(); 
        });

        return {
            state,
            users,
            getUsers,
            userOptions,
            setUser,
            generateReport
        }
    }
}
</script>