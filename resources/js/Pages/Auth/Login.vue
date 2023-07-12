<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Google from "@/Components/SVG/Google.vue";
import {postStorageCleaner} from "@/Services/postStorageCleaner.js";
postStorageCleaner();

defineOptions({
    layout: GuestLayout,
})
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
     <transition
        enter-from-class="transition duration-1000 opacity-0"
        enter-to-class="transition duration-1000 opacity-100">
         <div v-if="isVisible">
             <Head title="Log in" />

             <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                 {{ status }}
             </div>
             <form @submit.prevent="submit">
                 <div>
                     <InputLabel for="email" value="Email" />

                     <TextInput
                         id="email"
                         type="email"
                         class="mt-1 block w-full"
                         v-model="form.email"
                         required
                         autocomplete="username"
                     />

                     <InputError class="mt-2" :message="form.errors.email" />
                 </div>

                 <div class="mt-4">
                     <InputLabel for="password" value="Password" />

                     <TextInput
                         id="password"
                         type="password"
                         class="mt-1 block w-full"
                         v-model="form.password"
                         required
                         autocomplete="current-password"
                     />

                     <InputError class="mt-2" :message="form.errors.password" />
                 </div>

                 <div class="block mt-4">
                     <label class="flex items-center">
                         <Checkbox name="remember" v-model:checked="form.remember" />
                         <span class="ml-2 text-sm text-gray-600">Remember me</span>
                     </label>
                 </div>

                 <div class="flex items-center justify-end mt-4">
                     <Link
                         v-if="canResetPassword"
                         :href="route('password.request')"
                         class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                     >
                         Forgot Password
                     </Link>
                     <PrimaryButton class="ml-4 text-sm" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                         Log in
                     </PrimaryButton>
                     <a href="/login/google" class="ml-4">
                         <Google class="w-7 h-7"/>
                     </a>
                 </div>
             </form>
         </div>
     </transition>
</template>
