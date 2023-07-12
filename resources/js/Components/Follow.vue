<script setup>
import {UserPlusIcon, UserMinusIcon} from "@heroicons/vue/24/outline/index.js";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    user_id:{
        type: Number,
        required: true
    },
    followed: {
        type: Boolean,
        required: true
    }
})
let followed = ref(props.followed);
const uri = `/user/${props.user_id}/follow`;
let processing = ref(false);

function follow () {
    if (processing.value === false) {
        router.post(uri, {}, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            onStart: () => {
                processing.value = true;
                followed.value = !followed.value;
            },
            onFinish: () => {
                processing.value = false;
            }
        });
    }
}
</script>

<template>
    <div class="hover:scale-125 cursor-pointer bg-white rounded-full p-1" @click="follow" v-if="$page.props.auth.user && $page.props.auth.user.id !== user_id">
        <UserPlusIcon v-if="!followed" class="w-6 h-6"/>
        <UserMinusIcon v-else class="w-6 h-6"/>
    </div>
</template>

<style scoped>

</style>
