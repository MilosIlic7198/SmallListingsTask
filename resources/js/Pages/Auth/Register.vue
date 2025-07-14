<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const frontendErrors = ref({});

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate name
    if (!form.name || typeof form.name !== "string") {
        frontendErrors.value.name = "The name is required.";
    } else if (form.name.length > 255) {
        frontendErrors.value.name =
            "The name must be less than 255 characters.";
    }

    // Validate email
    if (!form.email || typeof form.email !== "string") {
        frontendErrors.value.email = "The email is required.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        frontendErrors.value.email = "Please enter a valid email address.";
    } else if (form.email.length > 255) {
        frontendErrors.value.email =
            "The email must be less than 255 characters.";
    }

    // Validate password
    if (!form.password || typeof form.password !== "string") {
        frontendErrors.value.password = "The password is required.";
    } else if (form.password.length < 8) {
        frontendErrors.value.password =
            "The password must be at least 8 characters.";
    }

    // Validate password_confirmation
    if (
        !form.password_confirmation ||
        typeof form.password_confirmation !== "string"
    ) {
        frontendErrors.value.password_confirmation =
            "The password confirmation is required.";
    } else if (form.password_confirmation !== form.password) {
        frontendErrors.value.password_confirmation =
            "The passwords must match.";
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const submit = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
            frontendErrors.value = {};
        },
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
                    :class="{
                        'border-red-500':
                            form.errors.name || frontendErrors.name,
                    }"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <p
                    v-if="form.errors.name || frontendErrors.name"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.name || frontendErrors.name }}
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
                    :class="{
                        'border-red-500':
                            form.errors.email || frontendErrors.email,
                    }"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                />
                <p
                    v-if="form.errors.password || frontendErrors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password || frontendErrors.password }}
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
                    :class="{
                        'border-red-500':
                            form.errors.password_confirmation ||
                            frontendErrors.password_confirmation,
                    }"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <p
                    v-if="
                        form.errors.password_confirmation ||
                        frontendErrors.password_confirmation
                    "
                    class="mt-2 text-sm text-red-600"
                >
                    {{
                        form.errors.password_confirmation ||
                        frontendErrors.password_confirmation
                    }}
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
