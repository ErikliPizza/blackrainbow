<script setup>
import {TrashIcon} from "@heroicons/vue/24/outline/index.js";
import {defineEmits, onMounted, ref} from 'vue';
import Avatar from "@/Components/Avatar.vue";
import {useFormatDate} from "@/Composables/useFormatDate.js";

const props = defineProps({
    author: {
        type: Object,
        required: true
    },
    content: {
        type: String,
        required: true
    },
    destroy: {
        type: Boolean,
        default: false
    },
    created_at: {
        type: String,
        required: true
    },
})
const emit = defineEmits(['destroy']);

function deleteComment() {
    emit('destroy');
}

const { formatDate } = useFormatDate();
const created_at = formatDate(props.created_at);

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
        <div v-if="isVisible" class="flex py-2 flex-col md:flex-row items-start md:items-center space-y-1 md:space-y-0">
            <Link :href='"/user/"+author.id' class="flex-none ms-1.5">
                <Avatar :role="author.role" :path="author.avatar" h="h-16" w="w-16"/>
            </Link>
            <div class="bg-gradient-to-r from-green-50 from-0% via-fuchsia-50 via-25% to-violet-100 rounded-xl p-2 ms-1 flex-grow w-full">
                <p class="text-gray-800 break-all">{{ content }}</p>
                <div class="flex justify-between">
                    <div>
                        <span class="text-gray-500 text-sm">Posted by </span><span class="text-gray-700 text-sm truncate">{{ author.name }}</span><span class="text-gray-400 text-sm italic">&nbsp;{{ created_at }}</span>
                    </div>
                    <TrashIcon v-if="destroy" class="w-6 h-6 inline text-red-600 cursor-pointer hover:scale-125 duration-150" @click="deleteComment"/>
                </div>
            </div>
        </div>
    </transition>
</template>


