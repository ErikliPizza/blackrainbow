<script setup>

import {onMounted, ref} from "vue";

defineProps({
    tag: {
        type: String,
        required: true
    },
    inform: {
        type: Boolean,
        default: false
    },
    lstIndex: {
        type: Number
    }
});
const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});
</script>

<template>

    <transition
        mode="out-in"
        enter-from-class="transition duration-1000 opacity-0"
        enter-to-class="transition duration-1000 opacity-100">
        <div v-if="isVisible">
            <span class="clickable flex items-center justify-center italic text-sm font-medium mr-2 px-0.5 py-0.5 rounded-xl opacity-60 w-20 truncate"
                  :class="{
                        'text-gray-400': ! inform,
                        'text-black': inform
                  }"
            >
                <span class="truncate" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ tag }}</span>
                <span v-if="lstIndex">({{ lstIndex }})</span>
            </span>
        </div>
    </transition>

</template>

<style scoped>
.clickable {
    cursor: pointer;
}
</style>
