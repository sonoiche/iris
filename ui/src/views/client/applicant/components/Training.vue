<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">Title</th>
                    <th class="fw-bolder">Provider</th>
                    <th class="fw-bolder">Cert. No</th>
                    <th class="fw-bolder">Place Issue</th>
                    <th class="fw-bolder">Date Issue</th>
                    <th class="fw-bolder">Date Expiry</th>
                </tr>
            </thead>
            <tbody v-if="trainings.length">
                <tr v-for="(training, index) in trainings" :key="training">
                    <td>{{ index+1 }}</td>
                    <td class="align-middle">{{ training.title }}</td>
                    <td class="align-middle">{{ training.provider }}</td>
                    <td class="align-middle">{{ training.certificate_number }}</td>
                    <td class="align-middle">{{ training.place_issue }}</td>
                    <td class="align-middle">{{ training.date_issue_display }}</td>
                    <td class="align-middle">{{ training.date_expiry_display }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center">No records found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import trainingRepo from '@/repositories/applicants/training';

export default {
    props: {
        applicant_id: {
            type: [Number, String],
            default: 0
        }
    },
    setup(props) {
        const state = reactive({
            isLoading: true
        });
        const { trainings, getTrainings } = trainingRepo();

        onMounted( async () => {
            await getTrainings(props.applicant_id);
            state.isLoading = false;
        });

        return {
            state,
            trainings,
            getTrainings
        }
    },
}
</script>