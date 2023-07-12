<script setup>
import Header from "@/Components/Card/header.vue";
import Body from "@/Components/Card/body.vue";
import Footer from "@/Components/Card/footer.vue";
import {onMounted, ref} from 'vue';
import TagBadge from "@/Components/tagBadge.vue";
import {TagIcon} from "@heroicons/vue/20/solid/index.js";
import StyleBadge from "@/Components/styleBadge.vue";
import {useFormatDate} from "@/Composables/useFormatDate.js";
import Avatar from "@/Components/Avatar.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const borderColors = ['border-red-200', 'border-blue-200', 'border-green-200', 'border-yellow-200', 'border-purple-200', 'border-rose-200'];
const randomIndex = Math.floor(Math.random() * borderColors.length);
const randomBorderColor = ref(borderColors[randomIndex]);
const isFlipped = ref(false);

const cardClasses = `${randomBorderColor.value}`;
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

</script>

<template>
    <div :class="cardClasses" v-show="isVisible" class="flex flex-col bg-white rounded-lg shadow-lg overflow-hidden border h-96">
        <div v-show="isFlipped" class="text-center p-3" :key="1">
            <div class="flex flex-wrap items-center">
                <TagIcon class="w-6 h-6 text-blue-200" />
                <tag-badge v-for="tag in art.tags.slice(0,2)" :text="tag" />
            </div>
            <div class="flex flex-wrap items-center">
                <style-badge v-for="style in art.styles.slice(0,2)" :text="style" />
            </div>
            <Link :href='"/user/"+art.author.id' class="flex justify-center py-2">
                <avatar :role="art.author.role" :path="art.author.avatar" h="h-28" w="w-28"/>
            </Link>
            <p>
                <div class="inline-flex items-center justify-center px-3 py-1 m-1 bg-rose-50 rounded-lg">
                    <span class="text-gray-800 cursor-pointer text-sm font-bold">{{ art.author.name }}</span>
                </div>
            </p>
            <p class="italic text-sm">{{ created_at }}</p>
            <Footer :can="art.can" :views="art.views" :art_id="art.id" :likes="art.likes" :liked="art.liked" :comments="art.comments" @flip="() => isFlipped = !isFlipped"/>
        </div>

        <div v-show="!isFlipped" :key="2">
            <Header @headerClicked="$emit('headerClicked')" @destroy="()=>{isVisible = false;}" :id="art.id" :uri="art.uri" :author="art.author" :can="art.can" :views="art.views" :images="art.images"/>
            <div class="flex flex-col justify-between flex-1 p-4">
                <Body :title="art.title" :description="art.description" />
                <Footer :can="art.can" :views="art.views" :art_id="art.id" :likes="art.likes" :liked="art.liked" :comments="art.comments" @flip="() => isFlipped = !isFlipped"/>
            </div>
        </div>
    </div>
</template>


<div class="card">
<div class="card__img">
    <img src="https://picsum.photos/seed/picsum/1200/600" alt="Cover Image" class="object-cover h-40 w-full transition-transform duration-300 hover:scale-105 cursor-pointer">
</div>
<img src="https://picsum.photos/id/237/600/600" alt="Cover Image" class="card__avatar p-1">
<div class="card__title">Cameron Williamson</div>
<div class="card__subtitle">Web Development</div>
<div class="flex justify-between gap-x-2 p-2">
    <primary-button>button</primary-button>
    <primary-button>button</primary-button>
    <primary-button>button</primary-button>
</div>
</div>
