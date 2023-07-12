<script setup>
import threeBoard from "@/Components/threeBoard.vue";
import CardBoard from "@/Components/cardBoard.vue";
import Card from "@/Components/Card/card.vue";
import InfiniteLoading from "v3-infinite-loading";
import useInfiniteLoading from "@/Composables/useInfiniteLoading.js";
import ScrollToTop from "@/Components/ScrollToTop.vue";
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import TwoBoard from "@/Components/twoBoard.vue";
import {useFilterRequest} from "@/Composables/useFilterRequest.js";
import Popover from "@/Components/Popover.vue";
import Avatar from "@/Components/Avatar.vue";
import {meHavePosts} from "@/Services/postScrollCleaner.js";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
postStorageCleaner();
meHavePosts();

const props = defineProps({
    arts: {
        type: Object,
        required: true,
    },
    popularArts: {
        type: Object
    },
    mostUser: {
        type: Object
    }
});

const {
    lastClickedArt,
    isArts,
    load,
} = useInfiniteLoading(props);

</script>

<template>
    <Head title="Art Board"/>
    <scroll-to-top :data="isArts.length"/>

    <two-board>
        <Carousel :autoplay="6000" :wrap-around="true">
            <Slide v-for="slide in popularArts" :key="slide.id">
                <div class="carousel__item">
                    <Link :href="slide.uri" class="item">
                        <img :src="'../storage/article_images/' + slide.images[0].filename" alt="Cover Image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105 cursor-pointer">
                    </Link>
                    <Link :href='"/user/"+slide.author.id'>
                        <avatar :role="slide.author.role" :path="slide.author.avatar" h="h-14" w="w-14" class="avatar"/>
                    </Link>
                </div>
            </Slide>

            <template #addons>
                <Navigation />
                <Pagination />
            </template>
        </Carousel>
    </two-board>

    <div class="mx-auto max-w-fit flex items-center absolute left-96 z-10">
    </div>

    <threeBoard @onSearch="useFilterRequest">
        <div class="mx-auto flex justify-center">
            <popover :user="mostUser"/>
        </div>
        <card-board>
            <transition-group
                enter-from-class="transition duration-1000 opacity-0"
                enter-to-class="transition duration-1000 opacity-100"
                leave-from-class="transition duration-1000 opacity-100"
                leave-to-class="transition duration-1000 opacity-0"
            >
                <card v-for="art in isArts" :art="art" :key="art.id" @headerClicked="lastClickedArt = art.id" :id="art.id"/>
            </transition-group>
        </card-board>

        <InfiniteLoading @infinite="load"/>
    </threeBoard>
</template>

<style scoped>
.user__item {
    font-size: 20px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}
@media (max-width: 767px) {
    /* Mobile styles */
    .user__item {
        min-height: 30px;
        width: 100%;

    }
}

@media (min-width: 768px) {
    /* Desktop styles */
    .user__item {
        min-height: 30px;
        width: 20%;

    }
}
.carousel__item {
    position: relative;
    background-color: var(--vc-clr-primary);
    color: var(--vc-clr-white);
    font-size: 20px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.item {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar {
    position: absolute;
    bottom: -10.5%;
    left: 1.5%;
    transform: translate(-50%, -50%); /* Add this line to center the avatar inside the top left corner */
}

@media (max-width: 767px) {
    /* Mobile styles */
    .carousel__item {
        min-height: 150px;
        width: 100%;
        align-items: flex-start; /* Add this line to align the avatar to the top left corner */
    }

    .avatar {
        position: absolute;
        bottom: auto; /* Remove the bottom property */
        left: 5%; /* Adjust the left property as needed */
        top: 5%; /* Add this line to position the avatar at the top left corner */
        transform: translate(0%, 0%); /* Remove the translate properties */
    }
}

@media (min-width: 768px) {
    /* Desktop styles */
    .carousel__item {
        min-height: 350px;
        width: 70%;
    }
}


.carousel__slide {
    padding: 10px;
}

.carousel__prev,
.carousel__next {
    box-sizing: content-box;
    border: 5px solid white;
}
</style>
