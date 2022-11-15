<template>
    <div class="fv-row mb-0 fv-plugins-icon-container" :class="{ 'mb-3' : marginBottomOn, 'fv-plugins-bootstrap5-row-invalid' : errors && errors[id] }">
        <label :for="id" class="form-label fs-6 fw-bolder mb-3" v-if="label">{{ label }} <span v-if="isRequired" class="text-danger">*</span></label>
        <input 
            v-bind="$attrs"
            class="form-control form-control-solid"
            :class="{ 'is-valid' : hasValue[id], 'is-invalid' : errors && errors[id] }" 
            :id="id" 
            :placeholder="placeholder" 
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            @change="fieldChange"
        />
        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors[id]">{{ errors[id][0] }}</label>
    </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
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
            type: [String, Number, Array],
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
        }
    },
    setup(props) {
        const hasValue = ref([]);
        const fieldChange = (evt) => {
            let id = evt.target.id;
            if(evt.target.value) {
                hasValue.value[id] = true;
                if(props.errors) {
                    props.errors[id] = '';
                }

                setTimeout(() => {
                    hasValue.value[id] = false;
                }, 3000);
            } else {
                hasValue.value[id] = false;
            }
        }
        return {
            fieldChange,
            hasValue
        }
    }
})
</script>