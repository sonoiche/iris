<template>
    <div class="d-flex flex-wrap flex-stack">
        <div v-for="(v, index) in values" :key="index">
            <input
                class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover mx-1 my-2"
                type="number"
                pattern="[0-9]"
                :style="{
                    width: `${props.fieldWidth}px`,
                    height: `${props.fieldHeight}px`,
                }"
                :autoFocus="autoFocus && index === autoFocusIndex"
                :data-id="index"
                :value="v"
                :ref="
                    (el) => {
                    if (el) inputs[index + 1] = el;
                    }
                "
                v-on:input="onValueChange"
                v-on:focus="onFocus"
                v-on:keydown="onKeyDown"
                :required="props.required"
                :disabled="props.disabled"
                maxlength="1"
            />
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, toRef, onBeforeUpdate } from "vue";

    const props = defineProps({
        className: String,
        fields: {
            type: Number,
            default: 3,
        },
        fieldWidth: {
            type: Number,
            default: 56,
        },
        fieldHeight: {
            type: Number,
            default: 56,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        required: {
            type: Boolean,
            default: true,
        },
        title: String,
    });

    const emit = defineEmits(["change", "complete"]);

    const KEY_CODE = {
        backspace: 8,
        left: 37,
        up: 38,
        right: 39,
        down: 40,
    };
    const values = ref([]);
    const iRefs = ref([]);
    const inputs = ref([]);
    const fields = toRef(props, "fields");
    const autoFocusIndex = ref(0);
    const autoFocus = true;
    const initVals = () => {
        let vals;
        if (values.value && values.value.length) {
            vals = [];
            for (let i = 0; i < fields.value; i++) {
            vals.push(values.value[i] || "");
            }
            autoFocusIndex.value =
            values.value.length >= fields.value ? 0 : values.value.length;
        } else {
            vals = Array(fields.value).fill("");
        }
        iRefs.value = [];
        for (let i = 0; i < fields.value; i++) {
            iRefs.value.push(i + 1);
        }
        values.value = vals;
    };

    const onFocus = (e) => {
        e.target.select(e);
    };

    const onValueChange = (e) => {
        const index = parseInt(e.target.dataset.id);
        e.target.value = e.target.value.replace(/[^\d]/gi, "");
        // this.handleKeys[index] = false;
        if (e.target.value === "" || !e.target.validity.valid) {
            return;
        }
        let next;
        const value = e.target.value;
        
        values.value = Object.assign([], values.value);
        if (value.length > 1) {
            let nextIndex = value.length + index - 1;
            if (nextIndex >= fields.value) {
                nextIndex = fields.value - 1;
            }

            next = iRefs.value[nextIndex];
            const split = value.split("");
            split.forEach((item, i) => {
            const cursor = index + i;
            if (cursor < fields.value) {
                values.value[cursor] = item;
            }
            });
        } else {
            next = iRefs.value[index + 1];
            values.value[index] = value;
        }
        if (next) {
            const element = inputs.value[next];
            element.focus();
            element.select();
        }
        triggerChange(values.value);
    };

    const onKeyDown = (e) => {
        const index = parseInt(e.target.dataset.id);
        const prevIndex = index - 1;
        const nextIndex = index + 1;
        const prev = iRefs.value[prevIndex];
        const next = iRefs.value[nextIndex];
        switch (e.keyCode) {
            case KEY_CODE.backspace: {
                e.preventDefault();
                const vals = [...values.value];
                if (values.value[index]) {
                    vals[index] = "";
                    values.value = vals;
                    triggerChange(vals);
                } else if (prev) {
                    vals[prevIndex] = "";
                    inputs.value[prev].focus();
                    values.value = vals;
                    triggerChange(vals);
                }
                break;
            }

            case KEY_CODE.left:
                e.preventDefault();
                if (prev) {
                    inputs.value[prev].focus();
                }
                break;

            case KEY_CODE.right:
                e.preventDefault();
                if (next) {
                    inputs.value[next].focus();
                }
                break;

            case KEY_CODE.up:
            case KEY_CODE.down:
                e.preventDefault();
                break;

            default:
                // this.handleKeys[index] = true;
                break;
        }
    };

    const triggerChange = (values = values.value) => {
        const val = values.join("");
        emit("change", val);
        if (val.length >= fields.value) {
            emit("complete", val);
        }
    };

    initVals();
    onBeforeUpdate(() => {
    inputs.value = [];
});
</script>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>