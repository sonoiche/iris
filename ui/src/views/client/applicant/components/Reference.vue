<template>
    <div>
        <table class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th class="fw-bolder">#</th>
                    <th class="fw-bolder">Name</th>
                    <th class="fw-bolder">Position</th>
                    <th class="fw-bolder">Company</th>
                    <th class="fw-bolder">Contact Number</th>
                    <th class="fw-bolder">Relationship</th>
                </tr>
            </thead>
            <tbody v-if="references.length">
            <tr v-for="(reference, index) in references" :key="reference">
                <td class="text-center align-middle">{{ index+1 }}</td>
                <td class="align-middle">{{ reference.name }}</td>
                <td class="align-middle">{{ reference.position }}</td>
                <td class="align-middle">{{ reference.company }}</td>
                <td class="align-middle">{{ reference.contact_number }}</td>
                <td class="align-middle">{{ reference.relationship }}</td>
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
import referenceRepo from '@/repositories/applicants/reference';

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
        const { references, getReferences } = referenceRepo();

        onMounted( async () => {
            await getReferences(props.applicant_id);
            state.isLoading = false;
        });

        return {
            state,
            references,
            getReferences
        }
    },
}
</script>