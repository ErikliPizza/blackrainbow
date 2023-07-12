<script setup>
import threeBoard from "@/Components/threeBoard.vue";
import {PlusIcon} from "@heroicons/vue/24/solid/index.js";
import CardBoard from "@/Components/cardBoard.vue";
import Card from "@/Components/Card/card.vue";
import {router} from "@inertiajs/vue3";
import InfiniteLoading from "v3-infinite-loading";
import useInfiniteLoading from "@/Composables/useInfiniteLoading.js";
import {useFilterRequest} from "@/Composables/useFilterRequest.js";
import ScrollToTop from "@/Components/ScrollToTop.vue";
import {meHavePosts} from "@/Services/postScrollCleaner.js";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
postStorageCleaner();
meHavePosts();

window.addEventListener('popstate', (event) => {
    event.stopImmediatePropagation();
    router.reload({
        preserveState  : false,
        preserveScroll : false,
        replace        : true,
    });
});

const props = defineProps({
    arts: {
        type: Object,
        required: true,
    },
    count: {
        type: Number,
        required: false
    }
});

const {
    lastClickedArt,
    isArts,
    load,
} = useInfiniteLoading(props);



</script>

<template>
    <Head title="Art Board" />
    <scroll-to-top :data="isArts.length"/>
    <div class="py-16">
        <threeBoard @onSearch="useFilterRequest">
            <Link :href="route('arts.create')" v-if="route().current('arts')">
                <plus-icon class="w-7 h-7 text-green-500 font-bold cursor-pointer" />
            </Link>
            <div v-if="count" class="max-w-sm mx-auto">
                <p class="text-center label-text font-bold text-gray-400 hover:scale-125 hover:text-rose-500 italic text-sm cursor-none">You have {{count}} item in this request!</p>
            </div>
            <card-board>
                <transition-group
                    enter-from-class="transition duration-1000 opacity-0"
                    enter-to-class="transition duration-1000 opacity-100"
                    leave-from-class="transition duration-1000 opacity-100"
                    leave-to-class="transition duration-1000 opacity-0"
                >
                    <card v-for="art in isArts" :art="art" :key="art.id" @headerClicked="lastClickedArt = art.id" :id="art.id" />
                </transition-group>
            </card-board>

            <InfiniteLoading @infinite="load" />

        </threeBoard>
    </div>
</template>
