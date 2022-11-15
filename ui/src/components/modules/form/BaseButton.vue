<template>
    <div>
        <button class="btn btn-primary" :disabled="btnDisabled" @click="save">
            <span v-if="isClicked">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
            <span v-else v-text="btnText"></span>
        </button>
    </div>
</template>

<script>
import { ref } from '@vue/reactivity'
import { watchEffect } from '@vue/runtime-core';
export default {
    props: {
        btnText: {
            type: String,
            default: 'Save Changes'
        },
        success: {
            type: Boolean,
            default: false
        },
        btnDisabled: {
            type: Boolean,
            default: false
        }
    },
    setup(props, {emit}) {
        const isClicked = ref(false);
        const save = () => {
            isClicked.value = true;
            emit('submit-form');
        }

        watchEffect(() => {
            if(props.success == true) {
                isClicked.value = false;
            }
        });

        return {
            isClicked,
            save
        }
    },
}
</script>