<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CategoryNode from "@/Components/CategoryNode.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { watch, ref } from "vue";

defineProps({
    categories: {
        type: Array,
    },
});

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
            childCategories.value = [];
            form.child_id = null;
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
        <!-- Main content -->
        <div class="flex flex-col w-full min-h-screen">
            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-6 sm:px-8 lg:px-10">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6 text-gray-900">
                                <h2 class="text-2xl font-bold mb-6">
                                    Create Category
                                </h2>
                                <form @submit.prevent="submit">
                                    <!-- New Categories -->
                                    <div class="mb-4">
                                        <label
                                            for="newCategories"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            New Categories
                                        </label>
                                        <p
                                            v-if="form.errors.newCategories"
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{ form.errors.newCategories }}
                                        </p>
                                        <div
                                            v-for="(
                                                category, index
                                            ) in form.newCategories"
                                            :key="index"
                                            class="mt-2"
                                        >
                                            <div class="flex items-center">
                                                <input
                                                    v-model="category.name"
                                                    type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                            <p
                                                v-if="
                                                    form.errors[
                                                        `newCategories.${index}.name`
                                                    ]
                                                "
                                                class="mt-1 text-sm text-red-600"
                                            >
                                                {{
                                                    form.errors[
                                                        `newCategories.${index}.name`
                                                    ]
                                                }}
                                            </p>
                                            <p
                                                v-if="
                                                    form.errors[
                                                        `newCategories.required`
                                                    ]
                                                "
                                                class="mt-1 text-sm text-red-600"
                                            >
                                                {{
                                                    form.errors[
                                                        `newCategories.required`
                                                    ]
                                                }}
                                            </p>
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
                                        <label
                                            for="parent_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Top Category (Optional)
                                        </label>
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
                                        <p
                                            v-if="form.errors.parent_id"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ form.errors.parent_id }}
                                        </p>
                                    </div>

                                    <!-- Sub Categories -->
                                    <div
                                        v-if="childCategories.length > 0"
                                        class="mb-4"
                                    >
                                        <label
                                            for="child_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Sub Category (Optional)
                                        </label>
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
                                        <p
                                            v-if="form.errors.child_id"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ form.errors.child_id }}
                                        </p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div>
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                                        >
                                            Create
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
                            </div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto mt-6">
                        <div class="bg-white rounded-lg shadow-sm">
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
