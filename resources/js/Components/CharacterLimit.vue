<script setup>
defineProps({
    maxLength: {
        type: Number,
        required: true
    },
    currentLength: {
        type: Number,
        required: true
    }
})
</script>

<template>
    <span class="ms-4 text-gray-500 italic text-sm">{{ (maxLength-currentLength) }}</span>
</template>
