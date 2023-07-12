<script setup>
import {scrollToTop} from "@/Composables/scrollToTop.js";
import {ChevronUpIcon} from "@heroicons/vue/24/outline/index.js";
defineProps({
    data:{
        type: Number,
        required: true
    }
})
</script>

<template>
    <div class="bottom-5 right-5 fixed cursor-pointer z-10 opacity-50" @click="scrollToTop()">
        <div class="flex flex-col items-center">
            <ChevronUpIcon class="w-6 h-6 text-black" />
            <div class="absolute mt-4 text-sm body-text text-gray-500">{{ data }}</div>
        </div>
    </div>


</template>

<style scoped>

</style>
