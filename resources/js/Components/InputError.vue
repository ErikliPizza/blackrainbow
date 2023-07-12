<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-center text-red-600">
            {{ message }}
        </p>
    </div>
</template>
