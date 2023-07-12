<script setup>
defineProps({
    text: {
        type: String,
        required: true
    }
})

function clean () {
    sessionStorage.removeItem('/');
}
</script>

<template>
    <Link @click="clean()" :href="`/?style[]=${text}`">
        <div class="inline-flex items-center justify-center px-3 py-1 m-1 bg-rose-50 rounded-lg">
            <span class="text-gray-800 cursor-pointer text-sm">{{ text }}</span>
        </div>
    </Link>
</template>

<style scoped>

</style>
