<template>
    <div>
        <div class="modal" tabindex="-1" aria-hidden="true" :class="{ 'show' : active }">
            <div class="modal-dialog" :class="{ 'modal-lg' : size == 'lg', 'modal-xl' : size == 'xl' }">
                <div class="modal-content rounded">
                    <div class="modal-header justify-content-center border-0 pb-0">
                        <div class="mb-13 text-center">
                            <slot name="header" />
                        </div>
                    </div>
                    <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
                        <slot name="body" />
                        <div class="d-flex flex-center flex-row-fluid pt-12">
                            <slot name="footer" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" v-if="active"></div>
    </div>
</template>

<script>
export default {
    props: {
        active: {
            type: Boolean,
            deafult: false
        },
        size: {
            type: String,
            default: 'lg'
        }
    },
    setup() {
        
    },
}
</script>

<style scoped>
.show {
    display: block !important;
}
</style>