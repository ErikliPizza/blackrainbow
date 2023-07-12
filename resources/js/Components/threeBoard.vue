<script setup>
import {defineEmits, onMounted, ref} from 'vue'
import CCombo from "@/Components/CCombo.vue";
import OrderList from "@/Components/OrderList.vue";
import {RocketLaunchIcon, ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import {usePage} from "@inertiajs/vue3";
// define props
const filters = usePage().props.filters;
// define props

// parameters
const sidebarOpen = ref(false);
let searchValue = ref(filters.search ?? '');
let selectedStyles = ref(filters.style ?? []);
let selectedTags = ref(filters.tag ?? []);
let selectedOrder = ref(filters.order ?? 1);
let selectedUsers = ref(filters.author ?? []);
let touchSupported = ref();
// parameters

let rocketLaunched = ref(false);
const emit = defineEmits(['onSearch']);


// functions
function launchMe() {
    document.body.classList.remove('overflow-hidden')
    rocketLaunched.value = true;
    let credentials = {
        search: searchValue.value,
        style: selectedStyles.value,
        tag: selectedTags.value,
        order: selectedOrder.value,
        user: selectedUsers.value
    };
    emit('onSearch', credentials);
}
function closeSidebar() {
    sidebarOpen.value = false
    document.body.classList.remove('overflow-hidden')
}
const openSidebar = () => {
    sidebarOpen.value = true;
    document.body.classList.add('overflow-hidden')
};
const isTouchDevice = () => {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
};
// functions

const startX = ref(null);
const startTime = ref(null);

const onTouchStart = (event) => {
    startX.value = event.touches[0].clientX;
    startTime.value = Date.now();
};

const onTouchEnd = (event) => {
    const deltaX = event.changedTouches[0].clientX - startX.value;
    const deltaTime = Date.now() - startTime.value;

    if (deltaX < -175 && deltaTime < 500 && sidebarOpen.value === false) {
        openSidebar();
    }
};

onMounted(
    () => {
        touchSupported.value = isTouchDevice();
    }
)
</script>

<template>
    <div v-if="!touchSupported" class="fixed right-5 top-1/2 transform -translate-y-1/2">
        <ArrowLeftIcon class="w-7 h-7 text-neutral-400 cursor-pointer" @click="openSidebar" v-show="! sidebarOpen"/>
    </div>
    <div class="py-2" @touchstart="onTouchStart" @touchend="onTouchEnd">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-gradient-to-br from-indigo-50 from-0% via-white shadow-md shadow-neutral-400 sm:rounded-lg relative">

                <slot/>

                <div :class="{'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen}" class="fixed inset-y-0 right-0 max-w-xs w-full bg-gradient-to-br from-violet-100 via-rose-50 to-white shadow-md sm:rounded-l-lg overflow-y-auto transition-transform duration-300 transform z-20">
                    <button @click="closeSidebar()" class="absolute top-0 right-0 mt-4 mr-4 p-2 bg-gray-50 bg-opacity-20 rounded-full text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="flex flex-col items-center justify-center h-96">
                        <div class="p-3.5 mt-36">
                            <input
                                type="text"
                                v-model="searchValue"
                                maxlength="20"
                                class="text-center relative w-full overflow-hidden rounded-lg bg-white text-left border-none shadow-md focus:ring-0 focus:border-none sm:text-sm"
                                placeholder="search.."
                                @keydown.enter="launchMe"
                            />
                        </div>
                        <div class="p-4 z-50 mx-auto">
                            <CCombo v-model="selectedStyles" whatAmI="style"/>
                        </div>
                        <div class="p-4 z-40">
                            <CCombo v-model="selectedTags" whatAmI="tag"/>
                        </div>
                        <div class="p-4 z-30 mx-auto" v-if="! route().current('arts')">
                            <CCombo v-model="selectedUsers" whatAmI="author" :limit="1"/>
                        </div>
                        <div class="p-4 z-20">
                            <OrderList v-model="selectedOrder"/>
                        </div>
                        <div class="p-1 z-10">
                            <rocket-launch-icon :class="{ 'launch-animation': rocketLaunched }" class="w-10 h-10 text-rose-400 cursor-pointer hover:text-violet-300 hover:scale-90 duration-300 transition-all" @click="launchMe"/>
                        </div>
                    </div>
                </div>
                <div v-if="sidebarOpen" @click="closeSidebar" class="fixed inset-0 bg-black opacity-50 z-10"></div>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Styles to prevent scrolling on the main page */
.overflow-hidden {
    overflow: hidden !important;
}

.launch-animation {
    animation: rocket-launch 3s forwards;
    transition: transform 3s;
}

@keyframes rocket-launch {
    0% {
        transform: translateX(0%) rotate(0deg);
    }
    50% {
        transform: translateX(500%) rotate(60deg);
    }
    51% {
        opacity: 0;
    }
    52% {
        transition: transform 0s;
        transform: translateX(-600%) rotate(70deg);
    }
    53% {
        opacity: 1;
    }
    100% {
        transition: ease-in;
        transform: translateX(0%) rotate(0deg);
    }
}
</style>
