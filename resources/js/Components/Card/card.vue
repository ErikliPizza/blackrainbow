<script setup>
import {onMounted, ref} from 'vue';
import {useFormatDate} from "@/Composables/useFormatDate.js";
import {Carousel, Navigation, Pagination, Slide} from "vue3-carousel";
import StyleBadge from "@/Components/styleBadge.vue";
import TagBadge from "@/Components/tagBadge.vue";
import Footer from "@/Components/Card/footer.vue";
import {EyeIcon, PencilSquareIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";
import {router} from "@inertiajs/vue3";

const isProcessing = ref(false);
const borderColors = ['border-red-200', 'border-blue-200', 'border-green-200', 'border-yellow-200', 'border-purple-200', 'border-rose-200'];
const randomIndex = Math.floor(Math.random() * borderColors.length);
const randomBorderColor = ref(borderColors[randomIndex]);

const cardClasses = `border ${randomBorderColor.value}`;
const props = defineProps({
    art: {
        type: Object,
        required: true
    }
})
const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;

});

const { formatDate } = useFormatDate();
const created_at = formatDate(props.art.created_at);

function destroyArt() {
    if (isProcessing.value === false) {
        isProcessing.value = true;
        const uri = '/arts/' + props.art.id;
        router.delete(uri, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onSuccess: () => {
                isVisible.value = false;
            },
            onError: () => {
                console.log('failed');
            }
        });
    }
}
</script>

<template>
    <div class="card" :class="cardClasses" v-show="isVisible">
        <div class="card__img">
            <Carousel :wrap-around="true">
                <Slide v-for="image in art.images" :image="image.id" @click="$emit('headerClicked')">
                    <Link :href="art.uri" class="card__img">
                        <img :src="'../storage/article_images/'+image.filename" alt="Cover Image" class="rounded-lg p-1 h-40 w-full cursor-pointer">
                    </Link>
                </Slide>
                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>
            <div>
                <div class="absolute top-0 p-5">
                    <TrashIcon class="text-red-400 w-5 h-5 duration-100 hover:scale-125 cursor-pointer" v-if="art.can.edit" @click="destroyArt"></TrashIcon>
                </div>
                <div class="absolute top-0 right-0 p-5">
                    <PencilSquareIcon class="text-neutral-400 w-5 h-5 duration-100 hover:scale-125 cursor-pointer" v-if="art.can.edit"></PencilSquareIcon>
                </div>
            </div>
        </div>
        <Link :href="'/user/'+art.author.id" class="flex justify-center">
            <img :src="art.author.avatar" alt="Cover Image" class="card__avatar p-1">
        </Link>
        <div class="card__title flex justify-center items-center gap-x-2">
            {{ art.title }}
            <div class="flex items-center gap-x-2">
                <div class="flex items-center justify-between space-x-1">
                    <EyeIcon class="w-5 h-5 text-neutral-400" />
                    <span class="text-gray-600 truncate text-sm">
                    {{ art.views }}
                </span>
                </div>
            </div>
        </div>
        <div class="card__subtitle">
            <style-badge v-for="style in art.styles.slice(0,2)" :text="style" />
        </div>
        <div class="card__subtitle">
            <tag-badge v-for="tag in art.tags.slice(0,2)" :text="tag" />
        </div>
        <Footer :can="art.can" :views="art.views" :art_id="art.id" :likes="art.likes" :liked="art.liked" :comments="art.comments" @flip="() => isFlipped = !isFlipped"/>
    </div>

</template>

<style scoped>
.card {
    --main-color: #000;
    --submain-color: #78858F;
    --bg-color: #fff;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    position: relative;
    width: 100%;
    height: 410px;
    flex-direction: column;
    align-items: center;
    border-radius: 20px;
    background: var(--bg-color);
}

.card__img {
    height: 192px;
    width: 100%;
}

.card__img img {
    height: 100%;
    border-radius: 20px 20px 0 0;
}

.card__avatar {
    position: absolute;
    width: 90px;
    height: 90px;
    background: var(--bg-color);
    border-radius: 100%;
    justify-content: center;
    align-items: center;
    top: calc(50% - 57px);
}

.card__avatar img {
    width: 100px;
    height: 100px;
}

.card__title {
    text-align: center;
    margin-top: 60px;
    font-weight: 500;
    font-size: 18px;
    color: var(--main-color);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 100%; /* Ensure the title takes up the full width */
}

.card__subtitle {
    text-align: center;
    margin-top: 10px;
    font-weight: 400;
    font-size: 15px;
    color: var(--submain-color);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 100%; /* Ensure the title takes up the full width */
}
</style>
