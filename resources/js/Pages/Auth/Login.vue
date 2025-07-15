<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

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

const frontendErrors = ref({});

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate email
    if (!form.email || typeof form.email !== "string") {
        frontendErrors.value.email = "The email is required.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        frontendErrors.value.email = "Please enter a valid email address.";
    }

    // Validate password
    if (!form.password || typeof form.password !== "string") {
        frontendErrors.value.password = "The password is required.";
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const submit = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    form.post(route("login.store"), {
        onFinish: () => {
            form.reset("password");
            frontendErrors.value = {};
        },
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
                    :class="{
                        'border-red-500':
                            form.errors.email || frontendErrors.email,
                    }"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <p
                    v-if="form.errors.email || frontendErrors.email"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.email || frontendErrors.email }}
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
                    :class="{
                        'border-red-500':
                            form.errors.password || frontendErrors.password,
                    }"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <p
                    v-if="form.errors.password || frontendErrors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password || frontendErrors.password }}
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
