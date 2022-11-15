<template>
    <div>
        <modal :active="isActive">
            <template #header>
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Advance Filter</h1>
                </div>
            </template>
            <template #body>
                <loading v-if="page.isLoading" />
                <div v-else class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect 
                                label="Principals"
                                :options="principals"
                                :placeholder="`Select Principals`"
                                :multiple="true"
                                :defaultValue="filters.principals"
                                id="status"
                                @select-value="setPrincipal"
                                @remove-value="removePrincipal"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect 
                                label="Status"
                                :options="joborder_status"
                                :placeholder="`Select Status`"
                                :defaultValue="filters.status"
                                id="status"
                                @select-value="setStatus"
                            />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12">
                            <BaseSelect 
                                label="Assigned Users"
                                :options="users"
                                :placeholder="`Select Users`"
                                :multiple="true"
                                :defaultValue="filters.users"
                                id="assinged_users"
                                @select-value="setUser"
                                @remove-value="removeUser"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button class="btn btn-outline-danger fw-bold" @click="cancel">Cancel</button> &nbsp;&nbsp;
                <base-button :success="isSuccess" :btn-text="`Save Filter`" @submit-form="saveFilter" />
            </template>
        </modal>
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch } from 'vue';
import principalRepo from '@/repositories/employer/principal';
import userRepo from '@/repositories/settings/users';
import joborderRepo from '@/repositories/employer/joborder';

export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        isLoading: {
            type: Boolean,
            default: true
        }
    },
    setup(props, {emit}) {
        const form = reactive({
            status: {},
            principal_id: [],
            assigned_users: []
        });

        const page = reactive({
            isLoading: true
        });
        
        const joborder_status = [
            { id: 'Active', name: 'Active' },
            { id: 'Inactive', name: 'Inactive' }
        ];
        const { principals, getSelectPrincipal } = principalRepo();
        const { users, getSelectUser } = userRepo();
        const { filters, getFilters } = joborderRepo();
        const isSuccess = ref(true);

        const saveFilter = () => {
            localStorage.setItem('filter', JSON.stringify(form));
            isSuccess.value = false;
            setTimeout(() => {
                emit('save-filter');
            }, 2000);
            setTimeout(() => {
                isSuccess.value = true;
            }, 3000);
        }

        const cancel = () => {
            filters.value = [];
            emit('close-modal');
        }

        const setPrincipal = (value) => {
            form.principal_id.push(value);
        }

        const removePrincipal = (value) => {
            form.principal_id.splice(form.principal_id.indexOf(value), 1);
        }

        const setStatus = (value) => {
            form.status = value;
        }

        const setUser = (value) => {
            form.assigned_users.push(value);
        }

        const removeUser = (value) => {
            form.assigned_users.splice(form.assigned_users.indexOf(value), 1);
        }

        onMounted(() => {
            getSelectPrincipal();
            getSelectUser();
            
            if(localStorage.getItem('filter') !== null) {
                let filter = JSON.parse(localStorage.getItem('filter'));
                let current_assigned_users = filter.assigned_users;
                current_assigned_users.forEach(item => {
                    form.assigned_users.push(item);
                });

                let current_principal_id = filter.principal_id;
                current_principal_id.forEach(item => {
                    form.principal_id.push(item);
                });

                form.status = filter.status;
            }
        });

        watch(() => props.isActive, async () => {
            await getFilters();
            setTimeout(() => {
                page.isLoading = false;
            }, 1000);
        });

        return {
            form,
            page,
            saveFilter,
            cancel,
            joborder_status,
            setPrincipal,
            removePrincipal,
            setStatus,
            setUser,
            removeUser,
            principals,
            getSelectPrincipal,
            users,
            getSelectUser,
            isSuccess,
            filters,
            getFilters
        }
    },
}
</script>