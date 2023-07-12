<script setup>
import artFormModule from "@/Services/artFormModule.js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import StyleTag from "@/Components/StyleTag.vue";
import {onMounted, ref} from "vue";
import TextArea from "@/Components/TextArea.vue";
import CharacterLimit from "@/Components/CharacterLimit.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ImageUploader from "@/Components/ImageUploader.vue";
import Ticket from "@/Components/Ticket.vue";
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import {usePage} from "@inertiajs/vue3";
import OneBoard from "@/Components/oneBoard.vue";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
postStorageCleaner();

onMounted(
    () => {
        page.value = 1;
    }
)

defineProps({
    tags: {
        type: Array,
        required: true
    },
    styles: {
        type: Array,
        required: true
    },
});

const focusMode = ref(false);
const ticket = ref(true);
const styletag = ref(null)
const { artForm } = artFormModule;
const page = ref(0);

const submitArt = () => {
    artForm.post('/myArts', {
        onSuccess: () => {
            styletag.value.stateSuccess();
            artForm.reset();
        },
        onError: () => {
            styletag.value.stateError();
        },
        onFinish: () => {
            page.value = 1;
        }
    });
};
function next() {
    if (page.value === 1){
        checkForm();
    } else if (page.value === 2) {
        checkForm();
    }
}

function checkImages() {
    if (artForm.images.length < 1 || artForm.images.length > 3) {
        artForm.errors.images = 'At least one image!';
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'At least one image!',
        });
        page.value = 2;
        return;
    } else {
        delete artForm.errors.images;
    }
    submitArt();
}

function checkForm() {

    if (artForm.title.trim().length > 24 || artForm.title.trim().length < 1) {
        artForm.errors.title = 'Title is required';
        page.value = 1;
        return;
    } else {
        delete artForm.errors.title;
    }

    if (!/^\d+$/.test(artForm.hours_spent) || parseInt(artForm.hours_spent) > 9999) {
        artForm.errors.hours_spent = 'Spent Hours must be numeric';
        page.value = 1;
        return;
    } else {
        delete artForm.errors.hours_spent;
    }

    if (artForm.description.length < 100 || artForm.description.length > 255) {
        artForm.errors.description = 'Description should be between 100 and 255 character';
        page.value = 1;
        return;
    } else {
        delete artForm.errors.description;
    }

    if (artForm.styles.length < 1 || artForm.styles.length > 5) {
        artForm.errors.styles = 'Selected art styles should be between 1 and 5';
        page.value = 1;
        return;
    } else {
        delete artForm.errors.styles;
    }
    if (artForm.tags.length > 0) {
        const tagNames = artForm.tags.map(tag => tag.name);
        const uniqueTagNames = new Set(tagNames);

        if (tagNames.length !== uniqueTagNames.size) {
            artForm.errors.tags = 'Tags should be unique.';
            page.value = 1;
            return;
        }

        const nameRegex = /^[a-zA-Z0-9]+$/;
        const invalidTags = artForm.tags.filter(tag => !nameRegex.test(tag.name) || tag.name.length > 16);

        if (invalidTags.length > 0) {
            artForm.errors.tags = 'Your tag collection contains invalid tags or tags with more than 16 characters.';
            page.value = 1;
            return;
        }

        delete artForm.errors.tags;
    } else {
        delete artForm.errors.tags;
    }
    if (page.value===2) {
        checkImages();
    }
    else {
        transitionEnded.value = false;
        usePage().props.comp = false;
        page.value = 2;
    }
}


const transitionEnded = ref(true);

</script>

<template>
    <Head title="Create" />
    <transition name="fade" @after-leave="transitionEnded=true">
        <one-board v-show="page===1 && transitionEnded" :key="1">
            <div class="flex justify-center">
                <div
                    class="w-full sm:max-w-md mt-2">
                    <form @keydown.enter.prevent @submit.prevent="submitArt">
                        <div class="mb-4 grid-cols-2 gap-x-2.5 flex justify-between">
                            <div>
                                <InputLabel for="title" value="Title" class="text-center"/>
                                <TextInput
                                    :required="true"
                                    id="title"
                                    type="text"
                                    class="block w-full"
                                    maxlength="24"
                                    v-model="artForm.title"
                                />
                                <CharacterLimit :currentLength="artForm.title.length" :maxLength="24" />
                                <InputError class="mt-2" :message="artForm.errors.title" />
                            </div>
                            <div>
                                <InputLabel for="hours_spent" value="Hours Spent" class="text-center"/>
                                <TextInput
                                    id="hours_spent"
                                    type="number"
                                    class="block w-full"
                                    v-model="artForm.hours_spent"
                                />
                                <InputError class="mt-2" :message="artForm.errors.hours_spent" />
                            </div>
                        </div>

                        <div class="mb-4">
                                <TextArea
                                    :required="true"
                                    style="resize: none;"
                                    id="description"
                                    class="block w-full"
                                    rows="5"
                                    v-model="artForm.description"
                                    maxlength="255"
                                    minlength="100"
                                />
                            <CharacterLimit :currentLength="artForm.description.length" :maxLength="255" />
                            <InputError class="mt-2" :message="artForm.errors.description" />
                        </div>
                        <StyleTag :tags="tags" :styles="styles" :artForm="artForm" ref="styletag"/>
                    </form>
                </div>
            </div>
            <div class="flex justify-end">
                <div>
                    <secondary-button @click="next()" v-show="!artForm.processing" :class="{'heartbeat-animation':artForm.images.length>=3}">Next</secondary-button>
                </div>
            </div>
        </one-board>
    </transition>

    <transition name="fade" @after-leave="transitionEnded=true">
        <one-board v-if="page===2 && transitionEnded" key="element2" id="element2">
            <ImageUploader @hideTicket="ticket=false" @showTicket="ticket=true" @focusMod="(value) => {focusMode=value;}" v-model="artForm.images"/>
            <div class="flex justify-between" v-show="!focusMode">
                <div>
                    <secondary-button @click="() => {transitionEnded = false; page--;}" v-show="page>1">Previous</secondary-button>
                </div>
                <div>
                    <secondary-button @click="next()" v-show="!artForm.processing" :class="{'heartbeat-animation':artForm.images.length>=3}">Next</secondary-button>
                </div>
            </div>
        </one-board>
    </transition>


    <div class="fixed inset-y-0 right-1 flex flex-col justify-center items-center" v-if="ticket">
        <Ticket v-for="(image, index) in artForm.images" :key="image" :id="index" :image="image" v-model="artForm.images"/>
    </div>


</template>

<style scoped lang="scss">


.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

@media screen and (max-width: 768px) {
    /* Adjust transition or opacity for smaller screens */
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }
}


@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: scale(1);
    }
    40%, 43% {
        transform: scale(0.9);
    }
    70% {
        transform: scale(1.1);
    }
    90% {
        transform: scale(1.05);
    }
}

// Apply the animation to the element
.heartbeat-animation {
    animation: bounce 1s infinite;
}
</style>
