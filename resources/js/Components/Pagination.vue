<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            class="px-2 text-red-800"
            :class="{
                'text-gray-500': ! link.url,
                'font-bold': link.active
            }"
        />
    </div>
</template>

<script setup>
defineProps({
    links: Object,
})
</script>
