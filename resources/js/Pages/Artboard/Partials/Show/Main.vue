<script setup>
import {ClockIcon, TagIcon, PencilSquareIcon} from "@heroicons/vue/20/solid/index.js";
import {useFormatDate} from "@/Composables/useFormatDate.js";
import Likes from "@/Components/Likes.vue";
import StyleBadge from "@/Components/styleBadge.vue";
import TagBadge from "@/Components/tagBadge.vue";
import {ref} from "vue";
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';
import InnerImageZoom from 'vue-inner-image-zoom';

const props = defineProps({
    art: {
        type: Object,
        required: true
    }
})

const mainImage = ref('../storage/article_images/'+props.art.images[0].filename);

const { formatDate } = useFormatDate();
const created_at = formatDate(props.art.created_at);
const selected = ref(props.art.images[0].id);
function changeMainImage(id) {
    const image = props.art.images.find((image) => image.id === id);
    mainImage.value = '../storage/article_images/'+image.filename;
    selected.value = image.id;
}
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Image container -->
        <div class="mb-6">
            <inner-image-zoom
                :src="mainImage"
                :zoomSrc="mainImage"
                alt="Main Image"
                id="main-image"
                class="w-auto h-auto rounded-md"
            />

            <div class="flex mt-4 px-1" v-if="art.images.length > 1">
                <img
                    v-for="image in art.images"
                    :key="image.id"
                    :src="'../storage/article_images/' + image.filename"
                    alt="Thumbnail Image"
                    class="w-16 h-1/4 mr-2 thumbnail rounded-md"
                    :class="{'opacity-50': selected === image.id}"
                    @click="changeMainImage(image.id)"
                />
            </div>
        </div>

        <!-- Product details -->
        <div>
            <div class="mb-4 flex flex-col">
                <div class="flex justify-between">
                    <h1 class="text-3xl font-bold truncate">{{ art.title }}</h1>
                    <div class="items-center flex">
                        <Likes :likeCount="art.likes" :liked="art.liked" :art_id="art.id"/>
                    </div>
                </div>
                <Link :href="'/user/'+art.author.id" class="text-sm text-blue-500 px-0.5 truncate">{{ art.author.name }}</Link>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 body-text break-all">{{ art.description }}</p>
            </div>

            <div class="mb-4 flex justify-between">
                <div class="flex justify-center gap-x-4 px-2.5">
                    <div class="flex items-center" v-if="art.hours_spent">
                        <ClockIcon class="w-5 h-5 text-gray-700" />
                        <span>{{ art.hours_spent }}</span>
                    </div>
                </div>

                <span class="font-bold truncate">{{ created_at }}</span>
            </div>

            <div class="mb-1 flex flex-wrap">
                <style-badge v-for="style in art.styles.slice(0, 2)" :text="style" />
            </div>

            <div class="mb-4 flex flex-wrap items-center" v-if="art.tags.length > 0">
                <TagIcon class="w-6 h-6 text-blue-200" />
                <tag-badge v-for="tag in art.tags.slice(0, 3)" :text="tag" />
            </div>

            <div class="mb-4 flex justify-end" v-if="art.can.edit">
                <PencilSquareIcon class="w-6 h-6 text-gray-700 cursor-pointer"/>
            </div>

        </div>

    </div>
</template>


