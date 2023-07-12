<script setup>
import {TicketIcon} from "@heroicons/vue/24/outline/index.js";
import { onMounted, ref } from "vue";
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

const animations = ['shake', 'bounce', 'rotate'];
const currentAnimation = ref('');
const startAnimation = () => {
    const rd = animations[Math.floor(Math.random() * animations.length)];
    currentAnimation.value = rd;
    setTimeout(() => {
        startAnimation();
    }, 5000);
};
onMounted(() => {
    startAnimation();
});

const props = defineProps({
    image: {
        type: String,
        required: true
    },
    id: {
        type: Number,
        required: true
    },
    modelValue: {
        type: Array,
        required: true
    }
})


function remove() {
    const index = props.modelValue.findIndex(img => img.src === props.image.src);
    if (index !== -1) {
        props.modelValue.splice(index, 1);
    }
}
function show() {
    Swal.fire({
        title: "Like it? Or..",
        html: `<div style="display: flex; justify-content: center; overflow: hidden; padding-bottom: 60%; position: relative;">
  <img src="${props.image.src}" alt="Your Image" style="position: absolute; top: 0; width: 100%; height: 100%; object-fit: cover;">
</div>
`,
        showDenyButton: true,
        denyButtonText: `Destroy Me`,
        confirmButtonText: 'Love Me',
    }).then((result) => {
        if (result.isDenied) {
            Swal.fire('Removed', '', 'success');
            remove();
        }
    })
}
</script>

<template>
    <p @click="show()">
        <TicketIcon
            class="w-9 h-9 sm:w-10 sm:h-10 box z-50"
            :class="{
            'text-green-600': id === 1,
            'text-rose-200': id === 2,
            'text-violet-300': id === 3
            },
            currentAnimation"
        />
    </p>
</template>

<style scoped lang="scss">


@keyframes shake {
    0% {
        transform: translateX(0);
        color: #cc7979;
    }
    25% {
        transform: translateX(-10px);
        color: #333362;
    }
    50% {
        transform: translateX(10px);
        color: green;
    }
    75% {
        transform: translateX(-10px);
        color: #afaf45;
    }
    100% {
        transform: translateX(0);
    }
}

@keyframes bounce {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(0.5);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    20% {
        transform: rotate(20deg);
    }
    40% {
        transform: rotate(-20deg);
    }
    60% {
        transform: rotate(20deg);
    }
    80% {
        transform: rotate(-20deg);
    }
    100% {
        transform: rotate(0deg);
    }
}



.shake {
    animation: shake 2s ease-in-out 2;
}

.bounce {
    animation: bounce 1s 2;
}

.rotate {
    animation: rotate 1.2s ease-in-out 2;
}
</style>
