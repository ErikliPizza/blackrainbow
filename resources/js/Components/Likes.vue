<script setup>
import {HeartIcon} from "@heroicons/vue/24/outline/index.js";
import Modal from '@/Components/Modal.vue';
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import axios from "axios";
import LikedUsers from "@/Components/LikedUsers.vue";
import Heart from "@/Components/Heart.vue";

const props = defineProps({
    art_id:{
        type: Number,
        required: true
    },
    likeCount: {
        type: String,
        required:true
    },
    liked: {
        type: Boolean,
        required: true
    }
})

const showingUsers = ref(false);
let processing = ref(false);
let holdTimer = ref(null);
let isClickAndHold = ref(false);
const uri = `/arts/${props.art_id}/like`;
let users = ref([]);
const show = ref(false);
let liked = ref(props.liked);
let likeCount = ref(props.likeCount);
const startTimer = () => {
    holdTimer.value = setTimeout(getUsers, 700);
};

const getUsers = () => {
    if (users.value.length === 0) {
        const userUri = '/arts/' + props.art_id + '/likes';
        axios.get(userUri)
            .then(response => {
                users.value = response.data.users;
            })
            .catch(error => {
                console.error(error);
            });
    }
    showingUsers.value = true;
    isClickAndHold.value = true;
};
const closeModal = () => {
    showingUsers.value = false;
};
function likePost() {
    clearTimeout(holdTimer.value); // Clear the hold timer
    setTimeout(() => {
        if (!isClickAndHold.value) {
            if (processing.value === false) {
                router.post(uri, {}, {
                    preserveScroll: true,
                    preserveState: true,
                    replace: true,
                    onStart: () => {
                        processing.value = true;
                        liked.value = !liked.value;
                        liked.value === true ? likeCount.value++ : likeCount.value--;
                    },
                    onFinish: () => {
                        processing.value = false;
                    }
                });
            }
        }

        isClickAndHold.value = false;
    }, 50);

};

const cancelTimer = () => {
    clearTimeout(holdTimer.value);
    isClickAndHold.value = false;
};
</script>

<template>
    <div class="flex items-center">
        <HeartIcon
            @mousedown="startTimer"
            @mouseup="likePost"
            @mouseleave="cancelTimer"
            @touchstart="startTimer"
            @touchcancel="cancelTimer"
            @touchend="cancelTimer"
            class="text-red-500 w-7 h-7 cursor-pointer"
            :class="{'filled': liked}"
        />
        <span class="text-gray-500 italic text-sm mb-0.5">
            {{ likeCount }}
        </span>
    </div>

    <Modal :show="showingUsers" @close="closeModal">
        <div class="overflow-y-auto max-h-96 p-4 bg-gradient-to-r from-rose-50 to-blue-100">
            <div v-if="users.length>0">
                <LikedUsers v-for="user in users" :user="user"/>
            </div>
            <div v-else class="text-center text-sm">
                No likes yet.
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.filled {
    fill: #ef4444;
    animation: fillAnimation 1s linear forwards;
}

@keyframes fillAnimation {
    0% { fill: transparent; } /* Start with transparent fill */
    100% { fill: #ef4444; } /* End with desired fill color */
}




</style>
