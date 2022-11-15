<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">Education Level</th>
                    <th class="fw-bolder">Education Field</th>
                    <th class="fw-bolder">Course</th>
                    <th class="fw-bolder">School</th>
                    <th class="fw-bolder">Location</th>
                    <th class="fw-bolder">School Year</th>
                </tr>
            </thead>
            <tbody v-if="educations.length">
                <tr v-for="(education, index) in educations" :key="education">
                    <td>{{ index+1 }}</td>
                    <td>{{ education.education_level_name }}</td>
                    <td>{{ education.education_field?.name }}</td>
                    <td>{{ education.course }}</td>
                    <td>{{ education.school }}</td>
                    <td>{{ education.location }}</td>
                    <td>{{ education.school_year }}</td>
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
import educationRepo from '@/repositories/applicants/education';

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
        const { educations, getEducations } = educationRepo();

        onMounted( async () => {
            await getEducations(props.applicant_id);
            page.isLoading = false;
        });

        return {
            educations,
            getEducations
        }
    },
}
</script>