<script setup>
import {ref, watch} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { router } from "@inertiajs/vue3";
import {usePage} from "@inertiajs/vue3";
import { useToast } from 'vue-toastification';

const toast = useToast();


function successToast(msg) {
    page.props.flash.success = null;
    page.props.flash.created.msg = null;
    toast.info(msg);
}
function createdToast(msg, url) {
    page.props.flash.success = null;
    page.props.flash.created.msg = null;
    toast.success(msg, {
        closeOnClick: false,
        onClick: () => {router.visit(url)}
    });
}

const showingNavigationDropdown = ref(false);
const page = usePage();
const transitionInProgress = ref(false);
const nav = ref(null);

function beforeEnter(){
    transitionInProgress.value = true;
}
const enter = () => {
    transitionInProgress.value = true;
}
const afterEnter = () => {
    isShow();
}
const beforeLeave = () => {
    transitionInProgress.value = true;
}
const leave = () => {
    transitionInProgress.value = true;
}
const afterLeave = () => {
    isShow();
}

function isShow() {
    setTimeout(()=> {
        transitionInProgress.value = false;
    }, 200)
}

let successDiv = ref(null);

// Watch for changes in the flash message (including nested properties)
watch(successDiv, (newValue) => {
    if (newValue) {
        successToast(newValue.getAttribute('data'));
    }
});

let createdDiv = ref(null);

// Watch for changes in the flash message (including nested properties)
watch(createdDiv, (newValue) => {
    if (newValue) {
        createdToast(newValue.getAttribute('data'), newValue.getAttribute('url'));
    }
});

</script>

<template>
    <div v-if="$page.props.flash">
        <div v-if="page.props.flash.created.msg" ref="createdDiv" :data="page.props.flash.created.msg" :url="page.props.flash.created.url"></div>
        <div v-if="page.props.flash.success" ref="successDiv" :data="page.props.flash.success"></div>
    </div>
    <div>
        <div class="min-h-screen" >
            <transition
                name="slide-fade"
                @before-enter="beforeEnter"
                @enter="enter"
                @after-enter="afterEnter"
                @before-leave="beforeLeave"
                @leave="leave"
                @after-leave="afterLeave"
            >
            <nav class="bg-black" v-show="page.props.comp" ref="nav">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')" @click="showingNavigationDropdown = false">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-white"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div v-if="$page.props.auth.user" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('arts.likedIndex')" :active="route().current('arts.likedIndex')">
                                    Liked Arts
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user.role === 'artist'" :href="route('arts')" :active="route().current('arts')">
                                    My Arts
                                </NavLink>
                                <NavLink v-else >
                                    Upgrade My Account
                                </NavLink>
                            </div>
                        </div>

                        <div v-if="$page.props.auth.user" class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-violet-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="'/user/'+$page.props.auth.user.id"> Profile </DropdownLink>
                                        <DropdownLink :href="route('profile.edit')"> Settings </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <div v-else class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Link
                                    :href="route('login')"
                                    class="font-semibold text-white hover:text-gray-300 text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Log in</Link
                                >

                                <Link
                                    :href="route('register')"
                                    class="ml-4 font-semibold text-white hover:text-gray-300 text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Register</Link
                                >
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden hamburger">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    class="sm:hidden"
                >
                    <div
                        v-if="$page.props.auth.user"
                        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden"
                    >
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('arts.likedIndex')" :active="route().current('arts.likedIndex')">
                                Liked Arts
                            </ResponsiveNavLink>
                            <ResponsiveNavLink v-if="$page.props.auth.user.role === 'artist'" @click="showingNavigationDropdown = false" :href="route('arts')" :active="route().current('arts')">
                                My Arts
                            </ResponsiveNavLink>
                            <ResponsiveNavLink v-else>
                                Upgrade My Account
                            </ResponsiveNavLink>
                        </div>

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gray-200">
                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="'/user/'+$page.props.auth.user.id" @click="showingNavigationDropdown = false"> Profile </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('profile.edit')" @click="showingNavigationDropdown = false"> Settings </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Navigation Menu -->
                    <div
                        v-else
                        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden"
                    >
                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gray-200">

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('login')"> Log in </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('register')"> Register </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
            </transition>
            <!-- Page Content -->
            <transition name="slide-fade">
            <main v-show="!transitionInProgress">
                <slot />
            </main>
            </transition>
        </div>
    </div>
</template>

<style>

/* CSS for entering (showing) the dropdown menu */
.slide-fade-enter-active {
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(-10px);
}

/* CSS for when the dropdown menu has entered (shown) */
.slide-fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}

/* CSS for leaving (hiding) the dropdown menu */
.slide-fade-leave-active {
    transition: all 0.3s ease;
    opacity: 100%;
    transform: translateY(0);
}

/* CSS for when the dropdown menu has left (hidden) */
.slide-fade-leave-to {
    opacity: 0%;
    transform: translateY(-10px);
}

</style>
