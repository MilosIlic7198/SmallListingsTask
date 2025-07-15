<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const frontendErrors = ref({});

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate password
    if (!form.password || typeof form.password !== "string") {
        frontendErrors.value.password = "The password is required.";
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    form.delete(route("profile.destroy"), {
        onSuccess: () => closeModal(),
        onError: () => {
            passwordInput.value.focus();
            frontendErrors.value = {};
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
    frontendErrors.value = {};
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <button
            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
            @click="confirmUserDeletion"
        >
            Delete Account
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only"> Password </label>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        :class="{
                            'border-red-500':
                                form.errors.password || frontendErrors.password,
                        }"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />
                    <p
                        v-if="form.errors.password || frontendErrors.password"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.password || frontendErrors.password }}
                    </p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                        @click="closeModal"
                    >
                        Cancel
                    </button>
                    <button
                        class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
