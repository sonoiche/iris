<template>
    <div class="fv-row mb-0 fv-plugins-icon-container" :class="{ 'mb-3' : marginBottomOn, 'fv-plugins-bootstrap5-row-invalid' : errors && errors[id] }">
        <label :for="id" class="form-label fs-6 fw-bolder mb-3" v-if="label">{{ label }} <span v-if="isRequired" class="text-danger">*</span></label>
        <date-picker 
            v-model="date"
            format="MM/dd/yyyy"
            inputClassName="form-control form-control-solid fc-calendar"
            @update:modelValue="handleDate"
        ></date-picker>
        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors[id]">{{ errors[id][0] }}</label>
    </div>
</template>

<script>
import { defineComponent, ref, watchEffect } from 'vue';
export default defineComponent({
    props: {
        label: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        },
        modelValue: {
            type: [String, Date],
            default: ''
        },
        id: {
            type: String,
            default: ''
        },
        errors: {
            type: [Object, String, Array],
            default: '',
            required: false
        },
        isRequired: {
            type: Boolean,
            default: false
        },
        marginBottomOn: {
            type: Boolean,
            default: true
        },
        defaultValue: {
            type: [String,Date],
            default: ''
        }
    },
    setup(props, {emit}) {
        const hasValue = ref([]);
        const date = ref('');
        const fieldChange = (evt) => {
            let id = evt.target.id;
            if(evt.target.value) {
                hasValue.value[id] = true;
                if(props.errors) {
                    props.errors[id] = null;
                }
            } else {
                hasValue.value[id] = false;
            }
        }

        const handleDate = (event) => {
            emit('update:modelValue', event);
        }
        
        watchEffect(() => {
            date.value = props.defaultValue;
        });

        return {
            fieldChange,
            hasValue,
            handleDate,
            date
        }
    }
})
</script>