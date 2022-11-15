<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">Position</th>
                    <th class="fw-bolder">Duration</th>
                    <th class="fw-bolder">Company</th>
                    <th class="fw-bolder">Location</th>
                    <th class="fw-bolder">Department</th>
                </tr>
            </thead>
            <tbody v-if="employments.length">
                <tr v-for="(employment, index) in employments" :key="employment">
                    <td>{{ index+1 }}</td>
                    <td>{{ employment.position }}</td>
                    <td>{{ employment.work_experience }}</td>
                    <td>{{ employment.company_name }}</td>
                    <td>{{ employment.company_address }}</td>
                    <td>{{ employment.department }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">No records found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import employmentRepo from '@/repositories/applicants/employment';

export default {
    props: {
        applicant_id: {
            type: [Number, String],
            default: 0
        }
    },
    setup(props) {
        const page = reactive({
            isLoading: true
        });
        const { employments, getEmployments } = employmentRepo();

        onMounted( async () => {
            await getEmployments(props.applicant_id);
            page.isLoading = false;
        });

        return {
            employments,
            getEmployments
        }
    },
}
</script>