<template>
    <transition
        name="bounce"
    >
    <div
        v-show="show"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <div class="p-4 rounded-lg">
            <div class="text-center">
                <HeartIcon class="w-36 h-36 text-red-600"/>
            </div>
        </div>
    </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue';
import {HeartIcon} from "@heroicons/vue/20/solid/index.js";
const show = ref(false);

const showSplash = () => {
    show.value = true;
    setTimeout(() => {
        show.value = false;
    }, 1000); // Adjust the duration as per your preference
};

defineExpose({
    showSplash
})
</script>

<style scoped>
.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.25);
    }
    100% {
        transform: scale(1);
    }
}
</style>
