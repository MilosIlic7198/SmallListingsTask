<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    status: {
        type: String,
    },
});

const { props } = usePage();
const { errors, auth } = props;

const form = useForm({
    name: auth.name,
    email: auth.email,
});

const frontendErrors = ref({});

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate name
    if (!form.name || typeof form.name !== "string") {
        frontendErrors.value.name = "The name is required.";
    } else if (form.name.length > 255) {
        frontendErrors.value.name = "The name must not exceed 255 characters.";
    }

    // Validate email
    if (!form.email || typeof form.email !== "string") {
        frontendErrors.value.email = "The email is required.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        frontendErrors.value.email = "Please enter a valid email address.";
    } else if (form.email !== form.email.toLowerCase()) {
        frontendErrors.value.email = "The email must be in lowercase.";
    } else if (form.email.length > 255) {
        frontendErrors.value.email =
            "The email must not exceed 255 characters.";
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const submit = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    form.patch(route("profile.update"), {
        onFinish: () => {
            frontendErrors.value = {};
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
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
                    autocomplete="username"
                />
                <p
                    v-if="form.errors.email || frontendErrors.email"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.email || frontendErrors.email }}
                </p>
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
