<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CategoryNode from "@/Components/CategoryNode.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { watch, ref, computed } from "vue";

defineProps({
    categories: {
        type: Array,
    },
});

const { props } = usePage();
const { errors, auth, categories } = props;

const form = useForm({
    parent_id: null,
    child_id: null,
    newCategories: [],
});

const childCategories = ref([]);
const frontendErrors = ref({});
const allCategories = ref([]);

//Top categories
const topCategories = computed(() => {
    return allCategories.value.filter((cat) => cat.parent_id === null);
});

// All categories names for unique name validation
const allNames = ref([]);

// Recursive function to extract all category names
const extractNames = (categories) => {
    let names = [];

    categories.forEach((category) => {
        names.push(category.name);
        if (category.children && category.children.length > 0) {
            names = names.concat(extractNames(category.children));
        }
    });

    return names;
};

// Fetch child categories from parent_id selection
const fetchSubcategories = async (parentId, targetRef) => {
    try {
        const response = await axios.get(
            `/categories/${parentId}/subcategories`
        );
        targetRef.value = response.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

// Fetch all categories
const fetchAllCategories = async () => {
    try {
        const response = await axios.get("/categories/all");
        allCategories.value = response.data;
        allNames.value = extractNames(allCategories.value);
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

// Watch parent_id for changes
watch(
    () => form.parent_id,
    (newParentId) => {
        form.child_id = null;
        if (newParentId === null) {
            childCategories.value = [];
        } else {
            fetchSubcategories(newParentId, childCategories);
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

// Validate form
function validateForm() {
    frontendErrors.value = {}; // Reset errors

    // Validate newCategories
    if (!form.newCategories.length) {
        frontendErrors.value.newCategories =
            "At least one category is required.";
    }

    const nameRegex = /^[a-zA-Z\s-]+$/;
    form.newCategories.forEach((category, index) => {
        if (!category.name || typeof category.name !== "string") {
            frontendErrors.value[`newCategories.${index}.name`] =
                "The category name is required.";
        } else if (category.name.length > 255) {
            frontendErrors.value[`newCategories.${index}.name`] =
                "The category name must be less than 255 characters.";
        } else if (!nameRegex.test(category.name)) {
            frontendErrors.value[`newCategories.${index}.name`] =
                "The category name can only contain letters, spaces, and hyphens.";
        } else {
            // Check for duplicate names among new categories
            const duplicate = form.newCategories.some(
                (other, otherIndex) =>
                    otherIndex !== index &&
                    other.name.toLowerCase() === category.name.toLowerCase()
            );
            if (duplicate) {
                frontendErrors.value[`newCategories.${index}.name`] =
                    "This category name is duplicated in the form.";
            }
            // Check for duplicate names against existing categories
            const exists = allCategories.value.some(
                (existing) =>
                    existing.name.toLowerCase() === category.name.toLowerCase()
            );
            if (exists) {
                frontendErrors.value[`newCategories.${index}.name`] =
                    "This category name already exists.";
            }
        }
    });

    // Validate parent_id
    if (
        form.parent_id &&
        !topCategories.value.some((category) => category.id === form.parent_id)
    ) {
        frontendErrors.value.parent_id = "Please select a valid top category.";
    }

    // Validate child_id
    if (
        form.child_id &&
        !childCategories.value.some((child) => child.id === form.child_id)
    ) {
        frontendErrors.value.child_id = "Please select a valid subcategory.";
    }

    return Object.keys(frontendErrors.value).length === 0;
}

// Submit the form
const submit = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

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

fetchAllCategories();
</script>

<template>
    <Head title="Manage Categories" />

    <div class="flex min-h-screen bg-gray-100">
        <div class="flex flex-col w-full min-h-screen">
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
                                <form
                                    @submit.prevent="submit"
                                    class="space-y-6"
                                >
                                    <!-- New Categories -->
                                    <div>
                                        <label
                                            for="newCategories"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            New Categories
                                        </label>
                                        <p
                                            v-if="
                                                form.errors.newCategories ||
                                                frontendErrors.newCategories
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.newCategories ||
                                                frontendErrors.newCategories
                                            }}
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
                                                    :id="`newCategory-${index}`"
                                                    class="mt-1 block w-full rounded-md border-gray-300 Prada shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    :class="{
                                                        'border-red-500':
                                                            form.errors[
                                                                `newCategories.${index}.name`
                                                            ] ||
                                                            frontendErrors[
                                                                `newCategories.${index}.name`
                                                            ],
                                                    }"
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
                                                    ] ||
                                                    frontendErrors[
                                                        `newCategories.${index}.name`
                                                    ]
                                                "
                                                class="mt-1 text-sm text-red-600"
                                            >
                                                {{
                                                    form.errors[
                                                        `newCategories.${index}.name`
                                                    ] ||
                                                    frontendErrors[
                                                        `newCategories.${index}.name`
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
                                    <div>
                                        <label
                                            for="parent_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Top Category (Optional)
                                        </label>
                                        <select
                                            v-model="form.parent_id"
                                            id="parent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.parent_id ||
                                                    frontendErrors.parent_id,
                                            }"
                                        >
                                            <option :value="null">
                                                No Top Category
                                            </option>
                                            <option
                                                v-for="category in topCategories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <p
                                            v-if="
                                                form.errors.parent_id ||
                                                frontendErrors.parent_id
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.parent_id ||
                                                frontendErrors.parent_id
                                            }}
                                        </p>
                                    </div>

                                    <!-- Sub Categories -->
                                    <div v-if="childCategories.length > 0">
                                        <label
                                            for="child_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Sub Category (Optional)
                                        </label>
                                        <select
                                            id="child_id"
                                            v-model="form.child_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.child_id ||
                                                    frontendErrors.child_id,
                                            }"
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
                                            v-if="
                                                form.errors.child_id ||
                                                frontendErrors.child_id
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.child_id ||
                                                frontendErrors.child_id
                                            }}
                                        </p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
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
                                                class="ml-2 text-sm text-gray-600"
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
                                    <div
                                        v-if="allCategories.length != 0"
                                        v-for="category in allCategories"
                                        :key="category.id"
                                    >
                                        <CategoryNode
                                            :allNames="allNames"
                                            :category="category"
                                            :depth="0"
                                            @updated="fetchAllCategories"
                                        />
                                    </div>
                                    <p v-else class="mt-1 text-black">
                                        No categories
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
