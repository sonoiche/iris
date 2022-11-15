<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container  mx-auto" style="width: 80%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="card mb-5 mb-xl-10">
                                            <div class="card-header border-0">
                                                <div class="card-title">
                                                    <h3 class="fw-bolder m-0">Update Manpower Request</h3>
                                                </div>
                                            </div>
                                            <div class="collapse show">
                                                <div class="card-body border-top p-9">
                                                    <ManpowerForm :job_order_id="job_order_id" @submit-status="submit" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="card mb-5 mb-xl-10">
                                            <div class="collapse show">
                                                <div class="card-body border-top p-9">
                                                    <ManpowerPosition :job_order_id="job_order_id" :position_id="pos_id" @submit-status="submitPosition" @submit-cancel="submitCancel" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-5 mb-xl-10">
                                            <div class="card-header border-0">
                                                <div class="card-title">
                                                    <h3 class="fw-bolder m-0">List of Positions</h3>
                                                </div>
                                            </div>
                                            <div class="card-body border-top p-9">
                                                <PositionList :job_order_id="job_order_id" :refresh-table="refresh_position_lists" @select-position="setPosition" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-5 mb-xl-10">
                                            <div class="card-header border-0">
                                                <div class="card-title">
                                                    <h3 class="fw-bolder m-0">Assigned Users</h3>
                                                </div>
                                            </div>
                                            <div class="card-body border-top p-9">
                                                <div class="d-flex">
                                                    <div class="assigned-user">
                                                        <BaseSelect 
                                                            :options="users"
                                                            :placeholder="`Select Users`"
                                                            :multiple="true"
                                                            :defaultValue="joborder.assigned_user_list"
                                                            @select-value="setUser"
                                                            @remove-value="removeUser"
                                                        />
                                                    </div>
                                                    <base-button :success="isSuccess" @submit-form="saveChanges" />
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
import { useRouter, useRoute } from 'vue-router';
import ManpowerForm from '@/views/client/manpower/components/Form.vue';
import ManpowerPosition from '@/views/client/manpower/components/PositionForm.vue';
import PositionList from '@/views/client/manpower/components/Positions.vue';
import { onMounted, ref } from 'vue';
import joborderRepo from '@/repositories/employer/joborder';
import userRepo from '@/repositories/settings/users';

export default {
    setup() {
        const router = useRouter();
        const route = useRoute();
        const job_order_id = ref(route.params.id);
        const pos_id = ref('');
        const { joborder, getJobOrder, updateJobOrderUsers } = joborderRepo();
        const { users, getSelectUser } = userRepo();
        const refresh_position_lists = ref(false);
        const isSuccess = ref(false);
        const assigned_users = ref([]);

        const submit = (event) => {
            if(event == 200) {
                router.push({
                    name: 'client.joborder'
                });
            }
        }

        const setPosition = (position_id) => {
            refresh_position_lists.value = false;
            pos_id.value = position_id;
        }

        const submitPosition = (event) => {
            if(event == 200) {
                refresh_position_lists.value = true;
                pos_id.value = '';
            }
        }

        const submitCancel = () => {
            refresh_position_lists.value = false;
            pos_id.value = '';
        }

        const saveChanges = async () => {
            let formData = new FormData();
            formData.append('assigned_users', assigned_users.value ?? '');
            formData.append('_method', 'PUT');
            await updateJobOrderUsers(formData, job_order_id.value);
            isSuccess.value = true;
        }

        const setUser = (value) => {
            assigned_users.value.push(value);
        }

        const removeUser = (value) => {
            assigned_users.value.splice(assigned_users.value.indexOf(value), 1);
        }

        onMounted( async () => {
            getSelectUser();
            await getJobOrder(job_order_id.value);
            let current_assigned_users = joborder.value.assigned_user_list;
            current_assigned_users.forEach(item => {
                assigned_users.value.push(item.id);
            });
        });

        return {
            job_order_id,
            pos_id,
            refresh_position_lists,
            submit,
            setPosition,
            submitPosition,
            submitCancel,
            joborder,
            getJobOrder,
            updateJobOrderUsers,
            isSuccess,
            saveChanges,
            users,
            getSelectUser,
            setUser,
            removeUser
        }
    },
    components: {
        ManpowerForm,
        ManpowerPosition,
        PositionList
    }
}
</script>

<style scoped>
.assigned-user {
    width: 85%;
    margin-right: 15px;
}
</style>