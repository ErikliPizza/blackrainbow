<script setup>
import {EyeIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";
import {Carousel, Navigation, Pagination, Slide} from "vue3-carousel";
import {router} from "@inertiajs/vue3";
import {defineEmits, ref} from "vue";

const emit = defineEmits(['destroy']);
const isProcessing = ref(false);
const props = defineProps({
    id: {
      type: Number,
      required: true
    },
    uri: {
        type: String,
        required: true
    },
    author: {
        required: true
    },
    can: {
        type: Object
    },
    views: {
        type: String,
        required: true
    },
    images: {
        required: true
    }
})

function destroyArt() {
    if (isProcessing.value === false) {
        isProcessing.value = true;
        const uri = '/arts/' + props.id;
        router.delete(uri, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onSuccess: () => {
                emit('destroy');
            },
            onError: () => {
                alert('failed');
            }
        });
    }
}

</script>

<template>
    <div class="relative py-2">
        <div>
            <Carousel :wrap-around="true">
                <Slide v-for="image in images" :image="image.id" @click="$emit('headerClicked')">
                    <Link :href="uri">
                        <img :src="'../storage/article_images/'+image.filename" alt="Cover Image" class="object-cover h-40 w-full transition-transform duration-300 hover:scale-105 cursor-pointer">
                    </Link>
                </Slide>
                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
        </div>

        <div class="flex justify-between items-center px-6">
            <TrashIcon class="text-red-400 w-5 h-5 duration-100 hover:scale-125 cursor-pointer" v-if="can.edit" @click="destroyArt"></TrashIcon>

            <PencilSquareIcon class="text-neutral-400 w-5 h-5 duration-100 hover:scale-125 cursor-pointer" v-if="can.edit"></PencilSquareIcon>

            <div v-if="!can.edit"/>
            <div class="flex items-center justify-between space-x-1" v-if="!can.edit" >
                <EyeIcon class="w-5 h-5 text-neutral-400" />
                <span class="text-gray-600 truncate text-sm">
                    {{ views }}
                </span>
            </div>
        </div>
    </div>
</template>

