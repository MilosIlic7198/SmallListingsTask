<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
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

    form.post(route("admin.customers.store"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
            frontendErrors.value = {};
        },
    });
};
</script>

<template>
    <Head title="Create Customer" />

    <div class="flex min-h-screen bg-gray-100">
        <div class="flex flex-col w-full min-h-screen">
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h2 class="text-2xl font-bold mb-6">
                                Create New Customer
                            </h2>

                            <form @submit.prevent="submit" class="space-y-6">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        :class="{
                                            'border-red-500':
                                                form.errors.name ||
                                                frontendErrors.name,
                                        }"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <p
                                        v-if="
                                            form.errors.name ||
                                            frontendErrors.name
                                        "
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{
                                            form.errors.name ||
                                            frontendErrors.name
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        :class="{
                                            'border-red-500':
                                                form.errors.email ||
                                                frontendErrors.email,
                                        }"
                                        required
                                        autocomplete="username"
                                    />
                                    <p
                                        v-if="
                                            form.errors.email ||
                                            frontendErrors.email
                                        "
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{
                                            form.errors.email ||
                                            frontendErrors.email
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        for="password"
                                        class="block text-sm font-medium text-gray-700"
                                        >Password</label
                                    >
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        :class="{
                                            'border-red-500':
                                                form.errors.password ||
                                                frontendErrors.password,
                                        }"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <p
                                        v-if="
                                            form.errors.password ||
                                            frontendErrors.password
                                        "
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{
                                            form.errors.password ||
                                            frontendErrors.password
                                        }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700"
                                        >Confirm Password</label
                                    >
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        :class="{
                                            'border-red-500':
                                                form.errors
                                                    .password_confirmation ||
                                                frontendErrors.password_confirmation,
                                        }"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <p
                                        v-if="
                                            form.errors.password_confirmation ||
                                            frontendErrors.password_confirmation
                                        "
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{
                                            form.errors.password_confirmation ||
                                            frontendErrors.password_confirmation
                                        }}
                                    </p>
                                </div>

                                <div class="flex justify-end space-x-4">
                                    <button
                                        type="button"
                                        @click="
                                            $inertia.visit(
                                                route('admin.customers.index')
                                            )
                                        "
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                                    >
                                        Create
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
