<script setup>
import {ChatBubbleLeftIcon} from "@heroicons/vue/24/outline/index.js";

import Likes from "@/Components/Likes.vue";
defineProps({
    art_id: {
        type: Number,
        required: true
    },
    likes: {
        type: String,
        required: true
    },
    liked: {
        type: Boolean,
        required: true
    },
    comments: {
        type: String,
        required: true
    },
    can: {
        type: Object
    },

})
</script>

<template>
    <div class="flex justify-between items-center px-3">
        <div class="flex items-center justify-between space-x-1">
            <Likes :liked="liked" :likeCount="likes" :art_id="art_id"/>
        </div>

        <div class="flex justify-between -space-y-0.5 space-x-1 items-center">
            <ChatBubbleLeftIcon class="w-5 h-5 text-gray-600" />
            <span class="text-gray-600">
                {{ comments }}
            </span>
        </div>
    </div>

</template>
