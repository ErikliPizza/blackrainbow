<script setup>
import StyleBadge from "@/Components/styleBadge.vue";
import TagBadge from "@/Components/tagBadge.vue";

defineProps({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
})
</script>

<template>
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800 mb-2 truncate body-text text-center">{{ title }}</h2>
        <p class="text-gray-600 line-clamp-3 body-text">{{ description }}</p>
    </div>
</template>

