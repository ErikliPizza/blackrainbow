<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    required: {
        type: Boolean,
        default: false
    }
});

defineEmits(['update:modelValue']);

const input = ref(null);
const isRequired = ref(props.required);
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        :required="isRequired"
        class="border border-gray-500 focus:border-neutral-700 focus:bg-violet-50 text-center focus:shadow-neutral-400 focus:bg-opacity-25 text-gray-500 focus:text-neutral-700 focus:ring-0 rounded-2xl shadow-md"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
