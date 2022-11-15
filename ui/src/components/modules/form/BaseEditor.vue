<template>
    <editor
        :api-key="tinykey"
        :init="init"
        v-model="editorContent"
        @keyUp="saveContent"
    />
</template>

<script>
import Editor from '@tinymce/tinymce-vue';
import { defineComponent, watchEffect, ref } from 'vue';
import _debounce from 'lodash/debounce';

export default defineComponent({
    props: {
        message: {
            type: String,
            default: ''
        },
        editorHeight: {
            type: Number,
            default: 300
        }
    },
    setup(props, {emit}) {
        const tinykey = ref(process.env.VUE_APP_TINY_MCE_KEY);
        const init = {
            height: props.editorHeight,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount',
                'link image code preview'
            ],
            toolbar:
                'undo redo | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
        }

        const editorContent = ref('');

        watchEffect(() => {
            editorContent.value = props.message;
        })

        const saveContent = _debounce( function () {
            emit('save-content', editorContent.value);
        }, 800);

        return {
            tinykey,
            init,
            saveContent,
            editorContent
        }
    },
    components: {
        'editor': Editor
    }
})
</script>