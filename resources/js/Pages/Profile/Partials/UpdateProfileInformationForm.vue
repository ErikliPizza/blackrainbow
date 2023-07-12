<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Avatar from "@/Components/Avatar.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: '',
    email: '',
    instagram: '',
    twitter: '',
    nso: '',
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information and email address.
        </p>
    </header>
    <section class="mx-auto max-w-xl">
        <a href="https://en.gravatar.com/emails/" target="_blank" class="py-6 mx-auto flex justify-center">
            <avatar :role="user.role" :path="user.avatar" h="w-28 sm:w-14 lg:w-28" w="h-28 sm:h-14 lg:h-28" />
        </a>
        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    :placeholder="[[user.name]]"
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="instagram" value="instagram" />

                <TextInput
                    id="instagram"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.instagram"
                    :placeholder="[[user.instagram]]"

                />

                <InputError class="mt-2" :message="form.errors.instagram" />
            </div>
            <div>
                <InputLabel for="twitter" value="twitter" />

                <TextInput
                    id="twitter"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.twitter"
                    :placeholder="[[user.twitter]]"

                />

                <InputError class="mt-2" :message="form.errors.twitter" />
            </div>
            <div>
                <InputLabel for="nso" value="nso" />

                <TextInput
                    id="nso"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.nso"
                    :placeholder="[[user.nso]]"

                />

                <InputError class="mt-2" :message="form.errors.nso" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex justify-end items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
