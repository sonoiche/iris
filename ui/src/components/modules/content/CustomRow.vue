<template>
    <div class="row mb-7">
        <label class="fw-bold text-muted" :class="colClasses[0]" v-text="label"></label>
        <div :class="colClasses[1]">
            <span class="fw-bolder fs-6 text-gray-800" v-text="value"></span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        colClasses: {
            type: Array,
            default: ['col-lg-3', 'col-lg-9']
        },
        label: {
            type: String,
            default: ''
        },
        value: {
            type: [String, Number],
            default: ''
        }
    }
}
</script>