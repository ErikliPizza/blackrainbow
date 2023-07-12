<script setup>
import OneBoard from "@/Components/oneBoard.vue";
import {useFormatDate} from "@/Composables/useFormatDate.js";
import Instagram from "@/Components/SVG/Instagram.vue";
import Twitter from "@/Components/SVG/Twitter.vue";
import NSO from "@/Components/SVG/NSO.vue";
import Follow from "@/Components/Follow.vue";
import {ref} from "vue";
import axios from "axios";
import LikedUsers from "@/Components/LikedUsers.vue";
import Modal from "@/Components/Modal.vue";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
postStorageCleaner();

const showingFollowers = ref(false);
const showingFollowings = ref(false);
const usersFollower = ref([]);
const usersFollowing = ref([]);
const closeModal = () => {
    showingFollowings.value = false;
    showingFollowers.value = false;
};

function getFollowers(){
    showingFollowers.value = true;
    if (usersFollower.value.length === 0) {
        const userUri = '/user/' + props.user.id + '/followers';
        axios.get(userUri)
            .then(response => {
                usersFollower.value = response.data.users;
            })
            .catch(error => {
                console.error(error);
            });
    }
}

function getFollowings() {

    showingFollowings.value = true;
    if (usersFollowing.value.length === 0) {
        const userUri = '/user/' + props.user.id + '/followings';
        axios.get(userUri)
            .then(response => {
                usersFollowing.value = response.data.users;
                console.log(response.data.users);
            })
            .catch(error => {
                console.error(error);
            });
    }
}
const props = defineProps({
    user: {
        required: true,
        type: Object
    },
    tags: Object,
    styles: Object,
    articles: 0,
    likes: 0,
    comments: 0,
    followers: 0,
    following: 0
})

const { formatDate } = useFormatDate();
const created_at = formatDate(props.user.created_at);
</script>

<template>
    <Head :title="user.name"/>

    <one-board>
        <div class="flex flex-col md:flex-row items-center mb-4">
            <div class="relative">
                <img :src="user.avatar" alt="Profile Photo" class="w-20 h-20 rounded-full object-cover md:mr-4">
                <div class="absolute -bottom-1 right-2 rounded-full p-1">
                    <Follow :user_id="user.id" :followed="user.is_following"/>
                </div>
            </div>
            <div class="flex flex-col items-center md:items-start">
                <h2 class="text-2xl font-bold">{{ user.name }}</h2>
                <p class="text-gray-500">Registered at: {{ created_at }}</p>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="flex mx-auto overflow-x-auto hide-scrollbar">
                <div class="flex flex-col items-center md:items-start mr-8 mb-4 md:mb-0 whitespace-nowrap">
                    <p class="text-2xl font-bold">{{ articles }}</p>
                    <Link :href="'/?author[]='+user.id" class="text-gray-500">Arts</Link>
                </div>
                <div class="flex flex-col items-center md:items-start mr-8 mb-4 md:mb-0 whitespace-nowrap">
                    <p class="text-2xl font-bold">{{ likes }}</p>
                    <p class="text-gray-500">Likes</p>
                </div>
                <div class="flex flex-col items-center md:items-start mr-8 mb-4 md:mb-0 whitespace-nowrap">
                    <p class="text-2xl font-bold">{{ comments }}</p>
                    <p class="text-gray-500">Comments</p>
                </div>
                <div class="flex flex-col items-center md:items-start mr-8 mb-4 md:mb-0 whitespace-nowrap">
                    <p class="text-2xl font-bold">{{ followers }}</p>
                    <p class="text-blue-500 cursor-pointer" @click="getFollowers">Followers</p>
                </div>
                <div class="flex flex-col items-center md:items-start mr-8 mb-4 md:mb-0 whitespace-nowrap">
                    <p class="text-2xl font-bold">{{ following }}</p>
                    <p class="text-blue-500 cursor-pointer" @click="getFollowings">Following</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center md:items-start mt-4">
            <div class="flex mb-2">
                <a v-if="user.instagram" :href='"https://www.instagram.com/"+user.instagram' class="text-blue-500 hover:underline mr-2"><Instagram/></a>
                <a v-if="user.twitter" :href='"https://twitter.com/"+user.twitter' class="text-blue-500 hover:underline mr-2"><Twitter/></a>
                <a v-if="user.nso" :href='"https://nso.noircontact.tech/user/"+user.nso' class="text-blue-500 hover:underline"><NSO/></a>
            </div>
        </div>

        <div class="flex flex-col mt-4">
            <h3 class="text-lg font-bold">Top Tags</h3>
            <div class="flex mt-2 overflow-x-auto hide-scrollbar">
                <div
                    v-for="tag in tags"
                    :key="tag"
                    class="inline-flex items-center bg-violet-50 text-gray-700 rounded-lg px-2 py-1 mr-2"
                >
                    <Link :href="`/?tag[]=${tag.name}`" class="mr-1">{{ tag.name }}</Link>
                    <span class="h-5 w-5 rounded-full flex items-center justify-center text-xs bg-white font-bold">{{ tag.count }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col mt-4">
            <h3 class="text-lg font-bold">Top Categories</h3>
            <div class="flex mt-2 overflow-x-auto hide-scrollbar">
                <div
                    v-for="style in styles"
                    :key="style"
                    class="inline-flex items-center bg-rose-50 text-gray-700 rounded-lg px-2 py-1 mr-2"
                >
                    <Link :href="`/?style[]=${style.name}`" class="mr-1">{{ style.name }}</Link>
                    <span class="h-5 w-5 rounded-full flex items-center justify-center text-xs bg-white font-bold">{{ style.count }}</span>
                </div>
            </div>
        </div>

    </one-board>

    <Modal :show="showingFollowings" @close="closeModal">
        <div class="overflow-y-auto max-h-96 p-4 bg-gradient-to-r from-rose-50 to-blue-100">
            <div v-if="usersFollowing.length>0">
                <LikedUsers v-for="user in usersFollowing" :user="user"/>
            </div>
            <div v-else class="text-center text-sm">
                No following users yet.
            </div>
        </div>
    </Modal>

    <Modal :show="showingFollowers" @close="closeModal">
        <div class="overflow-y-auto max-h-96 p-4 bg-gradient-to-r from-rose-50 to-blue-100">
            <div v-if="usersFollower.length>0">
                <LikedUsers v-for="user in usersFollower" :user="user"/>
            </div>
            <div v-else class="text-center text-sm">
                No followers yet.
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    width: 0.5rem;
}

.hide-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.hide-scrollbar::-webkit-scrollbar-thumb {
    background-color: transparent;
}
</style>
