<script setup>
import { Cropper } from 'vue-advanced-cropper';
import Wrapper from "@/Components/Wrapper.vue";
import 'vue-advanced-cropper/dist/style.css';
import {defineEmits, onBeforeMount, onBeforeUnmount, onMounted, onUnmounted, ref, watch} from "vue";
import VerticalButtons from "@/Components/VerticalButtons.vue";
import SquareButton from "@/Components/SquareButton.vue";
import Swal from 'sweetalert2'
import {ArrowUpTrayIcon, TrashIcon, EyeIcon, EyeSlashIcon, PlusCircleIcon, XCircleIcon, CheckCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Flip from "@/Components/SVG/Flip.vue";
import Rotate from "@/Components/SVG/Rotate.vue";
import Maximize from "@/Components/SVG/Maximize.vue";
import Shy from "@/Components/SVG/Shy.vue";
import {usePage} from "@inertiajs/vue3";

let scrollTarget = ref(null);

const props = defineProps({
    modelValue: {
        type: Array,
        required: true
    }
})

onBeforeUnmount(
    () => {
        usePage().props.comp = true;
        document.body.classList.remove('overflow-hidden');
        emit('showTicket');
    }
)

const file = ref(null);
const focus = ref(false);
const isCropper = ref();
const minWidth = 600;
const minHeight = 360;
const previewCoordinates = ref({
    width: null,
    height: null,
});


const image = ref({
    src: null,
    type: null,
    x: null,
    y: null
});
const emit = defineEmits(['showTicket', 'hideTicket']);
watch(image, (newValue)=> {
    if (newValue.src) {
        if (scrollTarget.value) {
            scrollTarget.value.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }
        emit('hideTicket');
        document.body.classList.add('overflow-hidden');

    } else {
        emit('showTicket');
        setTimeout(()=> {
            document.body.classList.remove('overflow-hidden');
        }, 500);
    }
});

function defaultPosition() {
    return {
        left: 100,
        top: 100,
    };
}
function defaultSize({ imageSize, visibleArea }) {
    return {
        width: (visibleArea || imageSize).width,
        height: (visibleArea || imageSize).height,
    };
}
function percentsRestriction() {
    return {
        minWidth: minWidth,
        minHeight: minHeight,
    };
}
function flip(x,y) {
    const { image } = isCropper.value.getResult();
    if (image.transforms.rotate % 180 !== 0) {
        isCropper.value.flip(!x, !y);
    } else {
        isCropper.value.flip(x, y);
    }
}
function rotate(angle) {
    isCropper.value.rotate(angle);
}

function maximize() {
    const center = {
        left: isCropper.value.coordinates.left + isCropper.value.coordinates.width / 2,
        top: isCropper.value.coordinates.top + isCropper.value.coordinates.height / 2,
    };
    isCropper.value.setCoordinates([
        ({ coordinates, imageSize }) => ({
            width: imageSize.width,
            height: imageSize.height,
        }),
        ({ coordinates, imageSize }) => ({
            left: center.left - coordinates.width / 2,
            top: center.top - coordinates.height / 2,
        }),
    ]);
}

// upload

// This function is used to detect the actual image type
function getMimeType(file, fallback = null) {
    const byteArray = (new Uint8Array(file)).subarray(0, 4);
    let header = '';
    for (let i = 0; i < byteArray.length; i++) {
        header += byteArray[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}

function loadImage(event) {
    // Reference to the DOM input element
    const { files } = event.target;
    // Ensure that you have a file before attempting to read it
    if (files && files[0]) {
        // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded file before
        if (image.value.src) {
            alert('sa');
            URL.revokeObjectURL(image.value.src);
        }
        // 2. Create the blob link to the file to optimize performance
        const blob = URL.createObjectURL(files[0]);

        // Create a new FileReader to read this image binary data
        const reader = new FileReader();
        // Define a callback function to run when FileReader finishes its job
        reader.onload = (e) => {
            // Note: arrow function used here, so that "this.image" refers to the image of Vue component
            const newImage = {
                // Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
                src: blob,
                // Determine the image type to preserve it during extracting the image from canvas
                type: getMimeType(e.target.result, files[0].type)
            };

            // Check if the image type is PNG or JPEG
            if (newImage.type !== "image/png" && newImage.type !== "image/jpeg") {
                // Perform the necessary action (e.g., show an error message, reset the image, etc.)
                // In this example, we'll reset the image
                resetImage();
                URL.revokeObjectURL(blob);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please upload a PNG or JPEG image.',
                });
                return; // Stop the uploading process
            }

            // Create a new Image object to get the image dimensions
            const img = new Image();
            img.onload = () => {
                const updatedImage = {
                    ...newImage,
                    x: img.width,
                    y: img.height
                };
                // Check if the image dimensions meet your requirements
                if (updatedImage.x < minWidth || updatedImage.y < minHeight) {
                    resetImage();
                    URL.revokeObjectURL(blob);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Image must be at least ${minWidth}x${minHeight}, given: ${updatedImage.x}x${updatedImage.y}`,
                    });
                } else {
                    image.value = updatedImage;
                }
            };
            img.src = blob;

            function resetImage() {
                image.value = {
                    src: null,
                    type: null,
                    x: null,
                    y: null
                };
            }
        };
        // Start the reader job - read file as a data URL (base64 format)
        reader.readAsArrayBuffer(files[0]);
    }
}

function destroyed() {
    // Destroy the created images
    if (image.value && image.value.src instanceof File) {
        URL.revokeObjectURL(image.value.src);
    }
    // Clear the file input value
    if (file.value) {
        file.value.value = '';
    }
    // Reset the image object
    image.value = {
        src: null,
        type: null,
        x: null,
        y: null,
    };

    // Reset the cropper
    if (isCropper.value) {
        isCropper.value.reset();
    }
}


function save() {
    if (props.modelValue.length >= 3) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "You can add maximum 3 images",
        });
    } else {
        let uploaded = isCropper.value.getCanvas().toDataURL('image/jpeg');
        props.modelValue.push({
            src: uploaded
        });
        destroyed();
    }
}

watch(focus, (newValue) => {
    if (newValue === true) {
        const element = document.getElementById('oneBoard');
        updateElementClasses(document.body, ['bg-gradient-to-r', 'from-rose-50', 'via-35%', 'via-neutral-200', 'to-gray-100'], 'bg-black');
        updateElementClasses(element, ['bg-gradient-to-br', 'from-indigo-50', 'from-0%', 'via-white', 'shadow-md', 'shadow-neutral-400'], 'bg-black');
    } else {
        const element = document.getElementById('oneBoard');
        updateElementClasses(document.body, ['bg-black'], 'bg-gradient-to-r', 'from-rose-50', 'via-35%', 'via-neutral-200', 'to-gray-100');
        updateElementClasses(element, ['bg-black'], 'bg-gradient-to-br', 'from-indigo-50', 'from-0%', 'via-white', 'shadow-md', 'shadow-neutral-400');
    }
});

function updateElementClasses(element, classesToRemove, ...classesToAdd) {
    element.classList.remove(...classesToRemove);
    element.classList.add(...classesToAdd);
}

</script>

<template>
    <div class="h-14 flex justify-end" ref="scrollTarget">
        <Transition name="fade" mode="out-in">
            <div v-if="image.src">
                <EyeIcon class="w-7 h-7 text-gray-600 cursor-pointer" v-show="! focus" @click="focus = true, $emit('focusMod', true)"/>
                <EyeSlashIcon class="w-7 h-7 text-gray-600 cursor-pointer" v-show="focus" @click="focus = false, $emit('focusMod', false)"/>
            </div>
        </Transition>
    </div>

    <Wrapper class="cont">
        <cropper
            background-class="cropper-background"
            :src="image.src"
            :size-restrictions-algorithm="percentsRestriction"
            :stencil-props="{aspectRatio: 2/1.2}"
            :default-position="defaultPosition"
            :default-size="defaultSize"
            :auto-zoom="false"
            ref="isCropper"
        />

        <Transition name="fade" mode="out-in">
            <div v-show="!image.src && modelValue.length<3" class="flex flex-col justify-center items-center absolute">
                <ArrowUpTrayIcon class="w-full h-full text-gray-500 cursor-pointer" @click="$refs.file.click()" />
                <input type="file" hidden ref="file" @change="loadImage($event)" accept="image/*">
                <div class="flex justify-center">
                    <!-- Display checked icons -->
                    <template v-for="i in modelValue.length">
                        <CheckCircleIcon class="w-10 h-10 text-green-500"  />
                    </template>
                    <!-- Display circle icons -->
                    <template v-for="i in (3-modelValue.length)">
                        <XCircleIcon class="w-10 h-10 text-red-800" />
                    </template>
                </div>
            </div>
        </Transition>

        <Transition name="fade" mode="out-in">
        <div class="flex flex-col items-center absolute" v-if="modelValue.length>=3">
            <p class="text-sm text-color-changer text-center">
                YOU ARE READY TO GO! CLICK THE NEXT, PLEASE, PLEASE. PLEASE. PLEASE. NEXT. &nbsp;
                <span class="text-red-800 font-bold text-sm">
                 NOW
                </span>
            </p>
        </div>
        </Transition>

        <transition name="fade" >
            <vertical-buttons v-if="!focus">
                <section v-if="image.src">
                    <square-button title="Rotate Clockwise" @click="rotate(90)" v-if="image.y>=minWidth">
                        <Rotate class="w-7 h-7 text-black" />
                    </square-button>
                    <square-button class="text-red-500" title="Maximize" @click="maximize()">
                        <Maximize class="w-7 h-7 text-black" />
                    </square-button>
                    <square-button title="Flip Horizontal" @click="flip(true, false)">
                        <Flip class="w-7 h-7 text-black" />
                    </square-button>
                    <square-button title="Flip Vertical" @click="flip(false, true)">
                        <Flip class="w-7 h-7 text-black -rotate-90" />
                    </square-button>
                    <square-button class="mt-3" title="Unset" @click="destroyed()" v-if="image.src">
                        <TrashIcon class="w-6 h-6 text-violet-400" />
                    </square-button>
                    <square-button class="mt-1" title="Save" @click="save()" v-if="image.src">
                        <PlusCircleIcon class="w-6 h-6 text-lime-200 hover:scale-150 hover:text-green-600 duration-100" />
                    </square-button>
                </section>
            </vertical-buttons>
        </transition>
        <div class="size-info" v-if="previewCoordinates.width && previewCoordinates.height">
            <div>Width: {{ previewCoordinates.width }}px</div>
            <div>Height: {{ previewCoordinates.height }}px</div>
        </div>
    </Wrapper>
</template>

<style lang="scss">
@use "sass:math"; // Import the math module

.size-info {
    color: #5d4343;
    position: absolute;
    font-size: 10px;
    right: 10px;
    bottom: 10px;
    opacity: 0.5;
}

.cont {
    height: 80vh; /* Set initial height to 90% of viewport height */
    display: flex;
    justify-content: center;
    align-items: center;

    @media (max-width: 767px) {
        height: 55vh; /* Adjust height for smaller devices */
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        height: 60vh; /* Adjust height for medium-sized devices */
    }

    @media (min-width: 1024px) {
        height: 63vh; /* Adjust height for larger devices */
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translatey(30px);
}
// Define a mixin to generate random RGB color values
@mixin random-color {
    $red: random(255);
    $green: random(255);
    $blue: random(255);
    color: rgb($red, $green, $blue);
}

// Define the animation
// Define the animation
@keyframes color-change {
    $steps: 20; // Number of intermediate steps
    $step-percent: math.div(100%, $steps); // Use math.div() function

    @for $i from 0 through $steps {
        #{$i * $step-percent} {
            @include random-color;
        }
    }
}

// Apply the animation to the text
.text-color-changer {
    animation: color-change 10s infinite; // Adjust the duration as per your preference
}
</style>
