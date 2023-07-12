<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>

    <div class="min-h-screen px-2 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div
            class="w-full sm:max-w-md mt-2 px-6 py-4 overflow-hidden sm:rounded-lg"
        >
            <div>
                <Link href="/" style="-webkit-tap-highlight-color: transparent;">
                    <slot />
                </Link>
            </div>
        </div>
    </div>
</template>

<style>

</style>
