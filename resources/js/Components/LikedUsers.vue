<script setup>
import Avatar from "@/Components/Avatar.vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Follow from "@/Components/Follow.vue";
defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="flex items-center py-4 justify-between">
        <Link :href='"/user/"+user.id' class="flex items-center">
            <Avatar :role="user.role" :path="user.avatar" h="h-16" w="w-16" class="mr-4" />
            <span class="text-gray-700">{{ user.name }}</span>
        </Link>
        <Follow :followed="user.is_following" :user_id="user.id" class="px-4 bg-gradient-to-br from-indigo-50 from-0% via-white rounded-lg py-1"/>
    </div>
</template>

<style scoped>

</style>
