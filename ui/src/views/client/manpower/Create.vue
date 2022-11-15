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
                                        <div class="card-title">
                                            <h3 class="fw-bolder m-0">Add Manpower Request</h3>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <ManpowerForm @submit-status="submit" />
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
import { useRouter } from 'vue-router';
import ManpowerForm from '@/views/client/manpower/components/Form.vue';

export default {
    setup() {
        const router = useRouter();
        const submit = (event) => {
            if(event == 200) {
                router.push({
                    name: 'client.joborder'
                });
            }
        }

        return {
            submit
        }
    },
    components: {
        ManpowerForm
    }
}
</script>