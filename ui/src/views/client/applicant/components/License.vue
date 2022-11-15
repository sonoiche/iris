<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">License/Certification Name</th>
                    <th class="fw-bolder">License/Certification Number</th>
                    <th class="fw-bolder">Date Taken</th>
                    <th class="fw-bolder">Date Expire</th>
                </tr>
            </thead>
            <tbody v-if="licenses.length">
                <tr v-for="(license, index) in licenses" :key="license">
                    <td>{{ index+1 }}</td>
                    <td>{{ license.title }}</td>
                    <td>{{ license.license_number }}</td>
                    <td>{{ license.date_taken_display }}</td>
                    <td>{{ license.date_expiry_display }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center">No records found</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import licenseRepo from '@/repositories/applicants/license';

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
        const { licenses, getLicenses } = licenseRepo();

        onMounted( async () => {
            await getLicenses(props.applicant_id);
            state.isLoading = false;
        });

        return {
            state,
            licenses,
            getLicenses
        }
    },
}
</script>