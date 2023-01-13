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
                                                    <h3 class="fw-bolder m-0">Applicants Source</h3>
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
                                                            label="Source"
                                                            :options="sourceOptions"
                                                            :placeholder="`All Sources`"
                                                            id="source_id"
                                                            @select-value="setSource"
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
import sourceRepo from '@/repositories/settings/source';
import { useRouter } from 'vue-router';

export default {
    setup(props) {
        const router = useRouter();
        const { sources, getSources } = sourceRepo();
        const state = reactive({
            source_id: '',
            date: ''
        });

        const sourceOptions = computed(() => {
            const arr_sources = [];
            sources.value.forEach(item => {
                arr_sources.push({
                    id: item.id,
                    name: item.name
                });
            });

            return arr_sources;
        });

        const setSource = (value) => {
            state.source_id = value.id;
        }

        const generateReport = () => {
            const form = {
                source_id: state.source_id,
                from: (state.date) ? new Date(state.date[0]).toISOString() : '',
                to: (state.date) ? new Date(state.date[1]).toISOString() : ''
            }

            // localStorage.setItem('source-applicants', JSON.stringify(form));
            if(state.source_id) {
                const routeData = router.resolve({ name: 'client.reports.applicant.source.applicants', params: { id: state.source_id }, query: form });
                window.open(routeData.href, '_blank');
            } else {
                const routeData = router.resolve({ name: 'client.reports.applicant.source.lists', query: form });
                window.open(routeData.href, '_blank');
            }
        }

        onMounted(() => {
            const endDate = new Date();
            const startDate = new Date(new Date().setDate(endDate.getDate() - 7));
            state.date = [startDate, endDate];

            getSources(); 
        });

        return {
            state,
            sources,
            getSources,
            sourceOptions,
            setSource,
            generateReport
        }
    }
}
</script>