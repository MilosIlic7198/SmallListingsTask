<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
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

const startEditing = (category) => {
    isEditing.value = true;
    editForm.id = category.id;
    editForm.name = category.name;
};

const cancelEditing = () => {
    isEditing.value = false;
    editForm.reset();
};

const submitEdit = () => {
    editForm.patch(route("admin.categories.update", editForm.id), {
        onSuccess: () => {
            isEditing.value = false;
            editForm.reset();
            emit("updated"); // Emit updated for edit
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

const deleteCategory = (categoryId) => {
    deleteForm.delete(route("admin.categories.destroy", categoryId), {
        onSuccess: (response) => {
            // Check if there's a flash error message in the response
            const type = response.props.flash?.type;
            if (type == "error") {
                // Set the error for this category
                categoryErrors.value[categoryId] =
                    response.props.flash?.message;
            } else {
                // No error, proceed with emitting update
                emit("updated");
                // Clear any previous error
                categoryErrors.value[categoryId] = null;
            }
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <div :class="{ 'ml-6': depth > 0 }">
        <InputError
            v-if="categoryErrors[category.id]"
            class="mt-1 text-red-500"
            :message="categoryErrors[category.id]"
        />
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
                        <InputLabel
                            for="edit_name"
                            value="Category Name"
                            class="sr-only"
                        />
                        <TextInput
                            id="edit_name"
                            v-model="editForm.name"
                            type="text"
                            class="block w-64"
                            placeholder="Enter category name"
                        />
                        <InputError
                            class="mt-1"
                            :message="editForm.errors.name"
                        />
                    </div>
                    <PrimaryButton
                        type="submit"
                        :disabled="editForm.processing"
                    >
                        Save
                    </PrimaryButton>
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
                    @click="deleteCategory(category.id)"
                >
                    Delete
                </button>
            </div>
        </div>
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
                @edit="startEditing"
                @updated="emit('updated')"
            />
        </div>
    </div>
</template>
