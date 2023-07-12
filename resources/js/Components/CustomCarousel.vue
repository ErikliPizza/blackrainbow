<script setup>
import {Carousel} from "vue3-carousel";

const getItemsToShow = () => {
    if (isMobileDevice()) {
        return 1; // Set the number of items to show on mobile devices
    } else {
        return 2; // Set the number of items to show on desktop devices
    }
}
const isMobileDevice = () => {
    return window.innerWidth < 768; // Adjust the width value to your mobile breakpoint
}
</script>

<template>
    <carousel :i18n="{'itemXofY': ''}" :itemsToShow="getItemsToShow()" :snapAlign="'start'" class="overflow-x-auto whitespace-nowrap disable-scrollbar">
        <slot></slot>
    </Carousel>
</template>


<style scoped>
.disable-scrollbar {
    overflow: hidden;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer and Edge */
}

.disable-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari, and Opera */
}
</style>
