<template>
    <div class="fv-row mb-0 fv-plugins-icon-container" :class="{ 'mb-3' : marginBottomOn, 'fv-plugins-bootstrap5-row-invalid' : errors && errors[id] }">
        <label :for="id" class="form-label fs-6 fw-bolder mb-3" v-if="label">{{ label }} <span v-if="isRequired" class="text-danger">*</span></label>
        <multiselect
            v-model="modelSelect"
            :options="options"
            :placeholder="placeholder"
            :multiple="multiple"
            :taggable="taggable"
            @tag="addTag"
            @select="updateInput"
            @remove="removeTag"
            label="name"
            track-by="id"
        ></multiselect>
        <label class="fv-plugins-message-container invalid-feedback" v-if="errors && errors[id]">{{ errors[id][0] }}</label>
    </div>
</template>

<script>
import { defineComponent, ref, watch, watchEffect } from 'vue';

export default defineComponent({
    props: {
        label: {
            type: String,
            default: ''
        },
        id: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'Select Options'
        },
        errors: {
            type: [Object, String],
            default: {},
            required: false
        },
        isRequired: {
            type: Boolean,
            default: false
        },
        options: {
            type: Array,
            default: []
        },
        marginBottomOn: {
            type: Boolean,
            default: true
        },
        multiple: {
            type: Boolean,
            default: false
        },
        taggable: {
            type: Boolean,
            default: false
        },
        defaultValue: {
            type: Object,
            default: {}
        },
        isClear: {
            type: Boolean,
            default: false
        }
    },
    setup(props, { emit }) {
        const modelSelect = ref([]);
        const tagfocus = ref(null);
        const updateInput = (event) => {
            const value = {
                name: event.name,
                id: event.id
            }
            emit('select-value', value);
        }

        const removeTag = (event) => {
            emit('remove-value', event.id);
        }

        const addTag = (event) => {
            if(props.taggable) {
                const tag = {
                    name: event,
                    id: event
                }

                modelSelect.value.push(tag);
                emit('select-value', modelSelect.value);
            }
        }

        watchEffect(() => {
            let input = props.defaultValue;
            if(!props.multiple) {
                if(input.name !== undefined) {
                    modelSelect.value = props.defaultValue;
                }
            } else {
                if(input.length > 0) {
                    modelSelect.value = props.defaultValue;
                } else {
                    modelSelect.value = [];
                }
            }
        });

        watch(() => props.isClear, () => {
            if(props.isClear) {
                modelSelect.value = '';
            }
        });

        return {
            updateInput,
            removeTag,
            addTag,
            modelSelect
        }
    }
})
</script>

<style>
.h-46 {
    height: 46px;
}
.multiselect__tags {
    min-height: 30px;
    border: 1px solid #f4f1eb;
    background: #f4f1eb;
    font-size: 14px;
    color: #716D66;
}
.multiselect__input, .multiselect__single, .multiselect__content-wrapper {
    background: #f4f1eb;
    font-size: 14px;
    color: #716D66;
}
.multiselect__placeholder {
    margin-bottom: 7px;
}
.multiselect__current, .multiselect__select {
    line-height: 21px;
}
</style>