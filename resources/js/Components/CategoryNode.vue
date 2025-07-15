<script setup>
import Modal from "@/Components/Modal.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    category: Object,
    depth: Number,
});

const emit = defineEmits(["updated"]);

const page = usePage();

const isEditing = ref(false);
const editForm = useForm({
    id: null,
    name: "",
});

// Form for deletion
const deleteForm = useForm({});

// Reactive property to hold errors for individual categories
const categoryErrors = ref({});
const frontendErrors = ref({});

// Fetch all categories for unique name validation
const allCategories = ref([]);
const fetchAllCategories = async () => {
    try {
        const response = await axios.get("/categories/all");
        allCategories.value = response.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

const startEditing = (category) => {
    isEditing.value = true;
    editForm.id = category.id;
    editForm.name = category.name;
};

const cancelEditing = () => {
    isEditing.value = false;
    editForm.reset();
    frontendErrors.value = {};
};

const validateForm = () => {
    frontendErrors.value = {}; // Reset errors

    // Validate name
    if (!editForm.name || typeof editForm.name !== "string") {
        frontendErrors.value.name = "The category name is required.";
    } else if (editForm.name.length > 255) {
        frontendErrors.value.name =
            "The category name must be less than 255 characters.";
    } else if (!/^[a-zA-Z\s-]+$/.test(editForm.name)) {
        frontendErrors.value.name =
            "The category name can only contain letters, spaces, and hyphens.";
    } else {
        // Check for duplicate names (excluding the current category)
        const exists = allCategories.value.some(
            (category) =>
                category.id !== editForm.id &&
                category.name.toLowerCase() === editForm.name.toLowerCase()
        );
        if (exists) {
            frontendErrors.value.name = "This category name already exists.";
        }
    }

    return Object.keys(frontendErrors.value).length === 0;
};

const submitEdit = () => {
    if (!validateForm()) {
        console.error("Form validation failed", frontendErrors.value);
        return;
    }

    editForm.patch(route("admin.categories.update", editForm.id), {
        onSuccess: () => {
            isEditing.value = false;
            editForm.reset();
            frontendErrors.value = {};
            emit("updated");
        },
        onError: (errors) => {
            console.error("Backend errors:", errors);
        },
    });
};

const confirmingUserDeletion = ref(false);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    deleteForm.reset();
};

const deleteCategory = (categoryId) => {
    deleteForm.delete(route("admin.categories.destroy", categoryId), {
        onSuccess: (response) => {
            closeModal();
            const type = response.props.flash?.type;
            if (type === "error") {
                categoryErrors.value[categoryId] =
                    response.props.flash?.message;
            } else {
                emit("updated");
                categoryErrors.value[categoryId] = null;
            }
        },
        onError: (errors) => {
            categoryErrors.value[categoryId] = errors;
            console.error("Delete error:", errors);
        },
        onFinish: () => deleteForm.reset(),
    });
};
</script>

<template>
    <div :class="{ 'ml-6': depth > 0 }">
        <p v-if="categoryErrors[category.id]" class="mt-1 text-sm text-red-600">
            {{ categoryErrors[category.id] }}
        </p>
        <!-- Category Row -->
        <div
            class="flex items-center justify-between py-2 border-b border-gray-200"
        >
            <div class="flex items-center space-x-2">
                <span v-if="depth > 0" class="text-gray-500">↳</span>
                <span v-if="!isEditing" class="text-gray-900">{{
                    category.name
                }}</span>
                <!-- Edit Form -->
                <form
                    v-if="isEditing"
                    @submit.prevent="submitEdit"
                    class="flex items-center space-x-2"
                >
                    <div>
                        <label for="edit_name" class="sr-only">
                            Category Name
                        </label>
                        <input
                            id="edit_name"
                            v-model="editForm.name"
                            type="text"
                            class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            :class="{
                                'border-red-500':
                                    editForm.errors.name || frontendErrors.name,
                            }"
                            placeholder="Enter category name"
                        />
                        <p
                            v-if="editForm.errors.name || frontendErrors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ editForm.errors.name || frontendErrors.name }}
                        </p>
                    </div>
                    <button
                        type="submit"
                        :disabled="editForm.processing"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
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
                            v-if="editForm.recentlySuccessful"
                            class="text-sm text-gray-600"
                        >
                            Saved.
                        </p>
                    </Transition>
                    <button
                        type="button"
                        class="text-gray-500 hover:text-gray-700"
                        @click="cancelEditing"
                    >
                        Cancel
                    </button>
                </form>
            </div>
            <!-- Actions -->
            <div v-if="!isEditing" class="flex items-center space-x-4">
                <button
                    class="text-indigo-600 hover:text-indigo-800"
                    @click="startEditing(category)"
                >
                    Edit
                </button>
                <button
                    class="text-red-500 hover:text-red-700"
                    @click="confirmUserDeletion()"
                >
                    Delete
                </button>
            </div>
        </div>
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this category?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once this category is deleted, all of its descendants and
                    associated listings will be deleted.
                </p>

                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                        @click="closeModal"
                    >
                        Cancel
                    </button>
                    <button
                        class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
                        :disabled="deleteForm.processing"
                        @click="deleteCategory(category.id)"
                    >
                        Delete Category
                    </button>
                </div>
            </div>
        </Modal>
        <!-- Children -->
        <div
            v-if="category.children && category.children.length > 0"
            class="ml-4"
        >
            <CategoryNode
                v-for="child in category.children"
                :key="child.id"
                :category="child"
                :depth="depth + 1"
                @updated="emit('updated')"
            />
        </div>
    </div>
</template>
