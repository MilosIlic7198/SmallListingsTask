<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineOptions({ layout: null });

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in to" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
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
                    autofocus
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
                    autocomplete="current-password"
                />
                <p
                    v-if="form.errors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('register')"
                    class="text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Don't have an account?
                </Link>
                <button
                    type="submit"
                    class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Log in
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
