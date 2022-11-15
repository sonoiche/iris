<template>
    <div>
        <loading v-if="page.isLoading" />
        <div class="row" v-else>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <table class="table table-hover w-100">
                    <tbody>
                        <tr>
                            <td class="w-50"><strong>Position 1</strong></td>
                            <td class="w-50">{{ applicant.position_applied }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Source</strong></td>
                            <td class="w-50">{{ applicant.source?.name }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Expected Salary</strong></td>
                            <td class="w-50">{{ applicant.expected_salary }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Complete Name</strong></td>
                            <td class="w-50">{{ applicant.fullname }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Age</strong></td>
                            <td class="w-50">{{ applicant.age }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Date of Birth</strong></td>
                            <td class="w-50">{{ applicant.birthdate_display }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Birthplace</strong></td>
                            <td class="w-50">{{ applicant.birthplace }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Gender</strong></td>
                            <td class="w-50">{{ applicant.gender }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Height</strong></td>
                            <td class="w-50">{{ applicant.height }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Weight</strong></td>
                            <td class="w-50">{{ applicant.weight }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Civil Status</strong></td>
                            <td class="w-50">{{ applicant.civil_status }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Nationality</strong></td>
                            <td class="w-50">{{ applicant.nationality_name }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Present Address</strong></td>
                            <td class="w-50">{{ applicant.present_address }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Keywords</strong></td>
                            <td class="w-50">{{ applicant.keywords_display }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <table class="table table-hover w-100">
                    <tbody>
                        <tr>
                            <td class="w-50"><strong>Years of Experience</strong></td>
                            <td class="w-50"></td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Availability (from date added)</strong></td>
                            <td class="w-50">{{ applicant.availability }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Branch</strong></td>
                            <td class="w-50"></td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Landline</strong></td>
                            <td class="w-50">{{ applicant.landline }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Mobile Number (main)</strong></td>
                            <td class="w-50">{{ applicant.mobile_number }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Mobile Number (alt)</strong></td>
                            <td class="w-50">{{ applicant.alt_mobile_number }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Language Spoken & Written</strong></td>
                            <td class="w-50">{{ applicant.language }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Email Address</strong></td>
                            <td class="w-50">{{ applicant.email }}</td>
                        </tr>
                        <tr>
                            <td class="w-50"><strong>Religion</strong></td>
                            <td class="w-50"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue';
import applicantRepo from '@/repositories/applicants/applicant';

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
        const { applicant, getApplicant } = applicantRepo();

        onMounted( async () => {
            await getApplicant(props.applicant_id);
            page.isLoading = false;
        });

        return {
            applicant,
            page,
            getApplicant
        }
    },
}
</script>