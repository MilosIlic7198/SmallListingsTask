<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { watch, ref } from "vue";

defineProps({
    categories: {
        type: Array,
    },
});

const form = useForm({
    title: "",
    description: "",
    price: "",
    condition: "new",
    parent_id: null,
    child_id: null,
    grandchild_id: null,
    category_id: null,
    phone: "",
    location: "",
    image: null,
});

const imageInputRef = ref(null);
const childCategories = ref([]);
const grandchildCategories = ref([]);
const frontendErrors = ref({});

// Fetch subcategories from parent_id selection.
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

// Watch parent_id for changes
watch(
    () => form.parent_id,
    (newParentId) => {
        form.child_id = null;
        form.grandchild_id = null;
        grandchildCategories.value = [];
        if (newParentId) {
            fetchSubcategories(newParentId, childCategories);
        } else {
            childCategories.value = [];
        }
    }
);

// Watch child_id for changes
watch(
    () => form.child_id,
    (newChildId) => {
        form.grandchild_id = null;
        if (newChildId) {
            fetchSubcategories(newChildId, grandchildCategories);
        } else {
            grandchildCategories.value = [];
        }
    }
);

function submit() {
    form.category_id = form.grandchild_id || form.child_id || form.parent_id;

    if (!validateForm()) {
        console.log("Form front validation failed", frontendErrors.value);
        return;
    }

    form.post(route("shared.listings.store"), {
        onSuccess: () => {
            imageInputRef.value = null;
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}

function handleImageChange(event) {
    form.image = event.target.files[0];
}

function validateForm() {
    frontendErrors.value = {}; // Reset errors

    if (
        !form.title ||
        typeof form.title !== "string" ||
        form.title.length > 250
    ) {
        frontendErrors.value.title =
            "The title is required and must be less than 250 characters.";
    }

    if (
        !form.description ||
        typeof form.description !== "string" ||
        form.description.length > 500
    ) {
        frontendErrors.value.description =
            "The description is required and must be less than 500 characters.";
    }

    const priceRegex = /^\d{1,8}(\.\d{1,2})?$/;
    if (
        !form.price ||
        isNaN(form.price) ||
        form.price < 0 ||
        form.price > 99999999.99 ||
        !priceRegex.test(form.price)
    ) {
        frontendErrors.value.price =
            "The price must be a valid number with up to 8 digits and 2 decimal places.";
    }

    if (!["new", "used"].includes(form.condition)) {
        frontendErrors.value.condition =
            'The condition must be either "new" or "used".';
    }

    if (form.phone && !/^(\+\d{1,3}[- ]?)?\d{10}$/.test(form.phone)) {
        frontendErrors.value.phone =
            "The phone number must be a valid 10-digit number, optionally starting with a country code.";
    }

    if (!form.phone) {
        frontendErrors.value.phone = "The phone number is required.";
    }

    if (!form.location || form.location.length > 100) {
        frontendErrors.value.location =
            "The location is required and must be less than 100 characters.";
    }

    const categoryId = form.grandchild_id || form.child_id || form.parent_id;
    if (!categoryId) {
        frontendErrors.value.category_id = "Please select a valid category.";
    }

    // Validate image size if image is selected
    if (form.image && form.image.size > 2048 * 1024) {
        frontendErrors.value.image = "The image may not be larger than 2MB.";
    }

    return Object.keys(frontendErrors.value).length === 0;
}
</script>

<template>
    <Head title="My Listings" />

    <div class="flex min-h-screen bg-gray-100">
        <!-- Main content -->
        <div class="flex flex-col w-full min-h-screen">
            <!-- Authenticated layout -->
            <AuthenticatedLayout>
                <main class="flex-1 py-6 px-6 sm:px-8 lg:px-10">
                    <div class="max-w-7xl mx-auto">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        >
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6">
                                    Create New Listing
                                </h2>

                                <form
                                    @submit.prevent="submit"
                                    class="space-y-6"
                                >
                                    <div>
                                        <label
                                            for="title"
                                            class="block text-sm font-medium text-gray-700"
                                            >Title</label
                                        >
                                        <input
                                            id="title"
                                            v-model="form.title"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.title,
                                            }"
                                        />
                                        <p
                                            v-if="
                                                form.errors.title ||
                                                frontendErrors.title
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.title ||
                                                frontendErrors.title
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="description"
                                            class="block text-sm font-medium text-gray-700"
                                            >Description</label
                                        >
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.description,
                                            }"
                                        ></textarea>
                                        <p
                                            v-if="
                                                form.errors.description ||
                                                frontendErrors.description
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.description ||
                                                frontendErrors.description
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="price"
                                            class="block text-sm font-medium text-gray-700"
                                            >Price</label
                                        >
                                        <input
                                            id="price"
                                            v-model="form.price"
                                            type="number"
                                            step="0.01"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.price,
                                            }"
                                        />
                                        <p
                                            v-if="
                                                form.errors.price ||
                                                frontendErrors.price
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.price ||
                                                frontendErrors.price
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="condition"
                                            class="block text-sm font-medium text-gray-700"
                                            >Condition</label
                                        >
                                        <select
                                            id="condition"
                                            v-model="form.condition"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.condition,
                                            }"
                                        >
                                            <option value="new">New</option>
                                            <option value="used">Used</option>
                                        </select>
                                        <p
                                            v-if="
                                                form.errors.condition ||
                                                frontendErrors.condition
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.condition ||
                                                frontendErrors.condition
                                            }}
                                        </p>
                                    </div>
                                    <!-- Parent Category -->
                                    <div>
                                        <label
                                            for="parent_id"
                                            class="block text-sm font-medium text-gray-700"
                                            >Category</label
                                        >
                                        <select
                                            id="parent_id"
                                            v-model="form.parent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.category_id ||
                                                    frontendErrors.category_id,
                                            }"
                                        >
                                            <option :value="null">
                                                Select a category
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
                                            v-if="
                                                form.errors.category_id ||
                                                frontendErrors.category_id
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.category_id ||
                                                frontendErrors.category_id
                                            }}
                                        </p>
                                    </div>
                                    <!-- Child Category -->
                                    <div v-if="childCategories.length > 0">
                                        <label
                                            for="child_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Subcategory
                                        </label>
                                        <select
                                            id="child_id"
                                            v-model="form.child_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                            <option :value="null">
                                                Select a subcategory
                                            </option>
                                            <option
                                                v-for="child in childCategories"
                                                :key="child.id"
                                                :value="child.id"
                                            >
                                                {{ child.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <!-- Grandchild Category -->
                                    <div v-if="grandchildCategories.length > 0">
                                        <label
                                            for="grandchild_id"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Sub-Subcategory
                                        </label>
                                        <select
                                            id="grandchild_id"
                                            v-model="form.grandchild_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        >
                                            <option :value="null">
                                                Select a sub-subcategory
                                            </option>
                                            <option
                                                v-for="grandchild in grandchildCategories"
                                                :key="grandchild.id"
                                                :value="grandchild.id"
                                            >
                                                {{ grandchild.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            for="phone"
                                            class="block text-sm font-medium text-gray-700"
                                            >Phone</label
                                        >
                                        <input
                                            id="phone"
                                            v-model="form.phone"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.phone,
                                            }"
                                        />
                                        <p
                                            v-if="
                                                form.errors.phone ||
                                                frontendErrors.phone
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.phone ||
                                                frontendErrors.phone
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="location"
                                            class="block text-sm font-medium text-gray-700"
                                            >Location</label
                                        >
                                        <input
                                            id="location"
                                            v-model="form.location"
                                            type="text"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.location,
                                            }"
                                        />
                                        <p
                                            v-if="
                                                form.errors.location ||
                                                frontendErrors.location
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.location ||
                                                frontendErrors.location
                                            }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            for="image"
                                            class="block text-sm font-medium text-gray-700"
                                            >Image (optional)</label
                                        >
                                        <input
                                            id="image"
                                            type="file"
                                            ref="imageInputRef"
                                            accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                            @change="handleImageChange"
                                        />
                                        <p
                                            v-if="
                                                form.errors.image ||
                                                frontendErrors.image
                                            "
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{
                                                form.errors.image ||
                                                frontendErrors.image
                                            }}
                                        </p>
                                    </div>

                                    <div class="flex justify-end">
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            :disabled="form.processing"
                                        >
                                            Create Listing
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </AuthenticatedLayout>
        </div>
    </div>
</template>
