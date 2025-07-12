<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Sidebar from "@/Components/Sidebar.vue";
import CategoryNode from "@/Components/CategoryNode.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { watch, ref } from "vue";

defineProps({
    categories: {
        type: Array,
    },
});

const isSidebarOpen = ref(false);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const form = useForm({
    parent_id: null,
    child_id: null,
    newCategories: [],
});

const childCategories = ref([]);

// Fetch child categories from parent_id selection
const fetchChildCategories = async (parentId) => {
    const response = await axios.get(`/categories/${parentId}/subcategories`);
    childCategories.value = response.data;
};

// Watch parent_id for changes
watch(
    () => form.parent_id,
    (newParentId) => {
        if (newParentId === null) {
            childCategories.value = []; // Clear the child categories
            form.child_id = null; // Clear selected subcategory if any
        } else {
            fetchChildCategories(newParentId);
        }
    }
);

// Add a new category input
const addNewCategory = () => {
    form.newCategories.push({ name: "" });
};

// Remove new category input
const removeNewCategory = (index) => {
    form.newCategories.splice(index, 1);
};

// Submit the form
const submit = () => {
    form.post(route("admin.categories.store"), {
        onSuccess: () => {
            form.reset();
            fetchAllCategories();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const allCategories = ref([]);

// Fetch all categories
const fetchAllCategories = async () => {
    const response = await axios.get("/categories/all");
    allCategories.value = response.data;
};

fetchAllCategories();
</script>

<template>
    <Head title="Manage Categories" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <Sidebar :isOpen="isSidebarOpen" @toggle="toggleSidebar" />

        <!-- Main content -->
        <div
            class="flex flex-col w-full min-h-screen transition-all duration-300 lg:ml-64"
            :class="{ 'lg:ml-64': isSidebarOpen }"
        >
            <!-- Mobile toggle -->
            <button
                class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md"
                @click="toggleSidebar"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-6 sm:px-8 lg:px-10">
                    <div class="max-w-7xl mx-auto">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6 text-gray-900">
                                <h2 class="text-2xl font-bold mb-6">
                                    Create Category
                                </h2>
                                <form @submit.prevent="submit">
                                    <!-- New Categories -->
                                    <div class="mb-4">
                                        <InputLabel
                                            for="newCategories"
                                            value="New Categories"
                                        />
                                        <InputError
                                            class="mt-1"
                                            :message="form.errors.newCategories"
                                        />
                                        <div
                                            v-for="(
                                                category, index
                                            ) in form.newCategories"
                                            :key="index"
                                            class="mt-2"
                                        >
                                            <div class="flex items-center">
                                                <TextInput
                                                    v-model="category.name"
                                                    type="text"
                                                    class="block w-full"
                                                    :placeholder="`Enter category #${
                                                        index + 1
                                                    }`"
                                                />
                                                <button
                                                    type="button"
                                                    class="ml-2 text-red-500 hover:text-red-700"
                                                    @click="
                                                        removeNewCategory(index)
                                                    "
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    form.errors[
                                                        `newCategories.${index}.name`
                                                    ]
                                                "
                                            />
                                            <InputError
                                                class="mt-1"
                                                :message="
                                                    form.errors[
                                                        `newCategories.required`
                                                    ]
                                                "
                                            />
                                        </div>
                                        <button
                                            type="button"
                                            class="mt-2 text-indigo-600 hover:text-indigo-800"
                                            @click="addNewCategory"
                                        >
                                            + Add New Category
                                        </button>
                                    </div>

                                    <!-- Top Category -->
                                    <div class="mb-4">
                                        <InputLabel
                                            for="parent_id"
                                            value="Top Category (Optional)"
                                        />
                                        <select
                                            v-model="form.parent_id"
                                            id="parent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        >
                                            <option :value="null">
                                                No Top Category
                                            </option>
                                            <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.parent_id"
                                        />
                                    </div>

                                    <!-- Sub Categories -->
                                    <div
                                        v-if="childCategories.length > 0"
                                        class="mb-4"
                                    >
                                        <InputLabel
                                            for="child_id"
                                            value="Sub Category (Optional)"
                                        />
                                        <select
                                            id="child_id"
                                            v-model="form.child_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        >
                                            <option :value="null">
                                                No Sub Category
                                            </option>
                                            <option
                                                v-for="child in childCategories"
                                                :key="child.id"
                                                :value="child.id"
                                            >
                                                {{ child.name }}
                                            </option>
                                        </select>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.child_id"
                                        />
                                    </div>

                                    <!-- Submit Button -->
                                    <div>
                                        <PrimaryButton
                                            type="submit"
                                            :disabled="form.processing"
                                        >
                                            Create
                                        </PrimaryButton>
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
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto mt-6">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6 text-gray-900">
                                <h2 class="text-2xl font-bold mb-6">
                                    Edit or Delete Category
                                </h2>
                                <div class="space-y-4">
                                    <!-- Recursive Category List -->
                                    <div
                                        v-for="category in allCategories"
                                        :key="category.id"
                                    >
                                        <CategoryNode
                                            :category="category"
                                            :depth="0"
                                            @updated="fetchAllCategories"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
