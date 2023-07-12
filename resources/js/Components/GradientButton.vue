<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="bg-gradient-to-r from-gray-800 from-10% to-black transition-all duration-200 ease-in-out hover:scale-90 text-white font-bold py-1 px-4 rounded opacity-75"
    >
        <slot />
    </button>
</template>


