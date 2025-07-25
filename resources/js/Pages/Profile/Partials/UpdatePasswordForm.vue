<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const frontendErrors = ref({});

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate current_password
    if (!form.current_password || typeof form.current_password !== "string") {
        frontendErrors.value.current_password =
            "The current password is required.";
    }

    // Validate password
    if (!form.password || typeof form.password !== "string") {
        frontendErrors.value.password = "The new password is required.";
    } else if (form.password.length < 8) {
        frontendErrors.value.password =
            "The new password must be at least 8 characters.";
    }

    // Validate password_confirmation
    if (
        !form.password_confirmation ||
        typeof form.password_confirmation !== "string"
    ) {
        frontendErrors.value.password_confirmation =
            "The password confirmation is required.";
    } else if (form.password !== form.password_confirmation) {
        frontendErrors.value.password_confirmation =
            "The passwords do not match.";
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const updatePassword = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    form.put(route("password.update"), {
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
        onFinish: () => {
            frontendErrors.value = {};
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <label
                    for="current_password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Current Password
                </label>
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{
                        'border-red-500':
                            form.errors.current_password ||
                            frontendErrors.current_password,
                    }"
                    autocomplete="current-password"
                />
                <p
                    v-if="
                        form.errors.current_password ||
                        frontendErrors.current_password
                    "
                    class="mt-2 text-sm text-red-600"
                >
                    {{
                        form.errors.current_password ||
                        frontendErrors.current_password
                    }}
                </p>
            </div>

            <div>
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    New Password
                </label>
                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{
                        'border-red-500':
                            form.errors.password || frontendErrors.password,
                    }"
                    autocomplete="new-password"
                />
                <p
                    v-if="form.errors.password || frontendErrors.password"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ form.errors.password || frontendErrors.password }}
                </p>
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-700"
                >
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{
                        'border-red-500':
                            form.errors.password_confirmation ||
                            frontendErrors.password_confirmation,
                    }"
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
