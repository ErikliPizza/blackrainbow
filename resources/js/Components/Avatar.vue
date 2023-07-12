<script setup>
import {CheckBadgeIcon} from "@heroicons/vue/20/solid/index.js";
defineProps({
    w: {
        type: String,
        required: true
    },
    h: {
        type: String,
        required: true
    },
    role: {
        type: String,
        required: true
    },
    path: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="relative">
        <img class="rounded-full cursor-pointer hover:scale-105" :class="h, w" :src="path" alt="Avatar">
        <div class="absolute bottom-0 left-0" v-if="role==='artist'">
            <CheckBadgeIcon class="text-lime-200 w-7 h-7 duration-75 hover:scale-150"/>
        </div>
    </div>
</template>
