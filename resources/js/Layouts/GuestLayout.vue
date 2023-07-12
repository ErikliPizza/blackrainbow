<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from "@inertiajs/vue3";

const page = usePage()
const activeComponent = computed(() => page.component)

const showLogin = ref(true);
if (activeComponent.value === 'Auth/Register') {
    showLogin.value = true;
} else {
    showLogin.value = false;
}

const toggleLogin = () => {
    showLogin.value = !showLogin.value;
    if (showLogin.value) {
        router.visit('/register');
    } else {
        router.visit('/login');
    }
};


</script>

<template>

    <div class="min-h-screen px-2 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-white" />
            </Link>
        </div>
        <div class="w-full sm:max-w-md">
            <div @click="toggleLogin" class="rounded-xl bg-white flex items-center justify-between py-2 px-4" style="cursor: pointer; -webkit-tap-highlight-color: transparent;">
                <span
                    class="font-semibold text-not-selectable"
                    :class="{
                        'text-blue-500' : showLogin,
                        'bg-blue-500 text-white py-1 px-3 rounded-xl' : ! showLogin
                    }"
                >Log in</span>
                <span
                    class="font-semibold text-not-selectable"
                    :class="{
                        'text-blue-500' : ! showLogin,
                        'bg-blue-500 text-white py-1 px-3 rounded-xl' : showLogin
                    }"
                >Sign in</span>
            </div>
        </div>

        <div
            class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="h-96">
                <slot />
            </div>
        </div>
    </div>
</template>

<style>
.text-not-selectable {
    user-select: none;
    cursor: pointer;
}
</style>
