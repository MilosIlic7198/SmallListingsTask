<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register on" />

        <form @submit.prevent="submit">
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700"
                >
                    Name
                </label>
                <input
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="mt-4">
                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                >
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="mt-4">
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <p
                    v-if="form.errors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="mt-4">
                <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-700"
                >
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <p
                    v-if="form.errors.password_confirmation"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>
                <button
                    type="submit"
                    class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Register
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
