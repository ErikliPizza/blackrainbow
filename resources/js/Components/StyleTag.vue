<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Badge from "@/Components/Badge.vue";
import {useTagOperations} from "@/Composables/useTagOperations.js";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {Slide} from "vue3-carousel";
import CustomCarousel from "@/Components/CustomCarousel.vue";
import TagCarousel from "@/Components/TagCarousel.vue";
import { ref, watch } from "vue";
import {useAlphaNumeric} from "@/Composables/useAlphaNumeric.js";

let props = defineProps({
    tags: {
        type: Array,
        required: true
    },
    styles: {
        type: Array,
        required: true
    },
    artForm: {
        type: Object,
        required: true
    }
});


const { swapTag, addCustomTag, selectedStyles, existInForm, allTags } = useTagOperations(props.tags, props.styles, props.artForm);
const { val: newTag } = useAlphaNumeric();

// Watch for changes in the component's tags array
watch(() => props.tags, (newTags) => {
    allTags.value = newTags;
});

function stateSuccess() {
    selectedStyles.value = [];
    newTag.value = '';
}
function stateError() {
    console.log('not submitted');
}

defineExpose({
    stateSuccess,
    stateError
})

function handleKeyUp() {
    addCustomTag(newTag.value);
    newTag.value = '';
}
</script>
<template>
    <div>
        <InputLabel for="selectedStyles" :value="'Style (' + props.artForm.styles.length + ')'" class="text-center"/>
        <custom-carousel class="ms-8">
            <slide v-for="style in props.styles" :key="style.id" class="inline-block p-3">
                <div class="carousel__item truncate">
                    <InputLabel>
                        <Checkbox :value="style.id" v-model:checked="selectedStyles"/>
                        <span class="ml-2 text-sm text-gray-700">{{ style.name }}</span>
                    </InputLabel>
                </div>
            </slide>
        </custom-carousel>
        <InputError class="mt-2" :message="props.artForm.errors.styles" />
    </div>
    <hr class="py-2">
    <div>
        <InputLabel :value="'Selected Tags (' + props.artForm.tags.length + ')'" class="text-center"/>
        <span v-show="props.artForm.tags.length <= 0" class="py-3.5 w-full bg-neutral-200 rounded-full flex justify-center text-sm opacity-25">
            No Tag Selected Yet
        </span>
        <TagCarousel class="disable-scrollbar ms-8">
            <slide v-for="(tag, index) in props.artForm.tags" :key="tag.id" class="inline-block py-3">
                <badge :tag="tag.name" :lst-index="index === props.artForm.tags.length - 1 ? (index+1): null" @click="swapTag(tag.name)" :inform="existInForm(tag.name)" />
            </slide>
        </TagCarousel>
    </div>

    <div class="my-6">
        <TextInput
            v-model="newTag"
            type="text"
            class="mt-1 block w-full"
            @keyup.enter="handleKeyUp"
        />
        <InputError class="mt-2" :message="props.artForm.errors.tags" />
    </div>

    <div class="my-2">
        <TagCarousel class="disable-scrollbar ms-8">
            <slide v-for="tag in props.tags" :key="tag.id" class="inline-block">
                <div class="carousel__item">
                    <badge :tag="tag.name" @click="swapTag(tag.name)" :inform="existInForm(tag.name)"/>
                </div>
            </slide>
        </TagCarousel>
    </div>

</template>

