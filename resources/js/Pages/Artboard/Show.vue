<script setup>
import OneBoard from "@/Components/oneBoard.vue";
import Comments from "@/Pages/Artboard/Partials/Show/Comments.vue";
import Main from "@/Pages/Artboard/Partials/Show/Main.vue";
import {onMounted, ref} from "vue";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
postStorageCleaner();

const props = defineProps({
    art: {
        type: Object,
        required: true
    },
    comments:{
        type: Object,
        default: []
    },

})
const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
    <Head :title="art.title" />
    <transition
        mode="out-in"
        enter-active-class="fade-enter-active"
        enter-from-class="fade-enter-from"
        enter-to-class="fade-enter-to">
        <one-board v-if="isVisible">
            <div class="container mx-auto">
                <!-- Main section -->
                <Main :art="art"/>
                <!-- Comment section -->
                <Comments :comments="comments" :art_id="art.id" />
            </div>
        </one-board>
    </transition>
</template>

<style scoped>
.fade-enter-active {
    transition-property: opacity;
}

.fade-enter-from {
    opacity: 0;
}

.fade-enter-to {
    opacity: 1;
    transition-duration: 2000ms;
    transition-timing-function: ease; /* or any other easing function */
}
</style>
