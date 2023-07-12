<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block label-text text-sm font-bold mb-2">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
