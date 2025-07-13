<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import { useSidebarStore } from "@/stores/SidebarStore";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    categories: {
        type: Array,
    },
});

const emit = defineEmits(["toggle"]);
const store = useSidebarStore();

const form = useForm({
    category_id: null,
});

// Watch parent_id to fetch child categories
watch(
    () => store.parent_id,
    (newParentId) => {
        store.child_id = null;
        store.grandchild_id = null;
        store.grandchildCategories = [];
        store.fetchSubcategories(newParentId, store.childCategories);
    }
);

// Watch child_id to fetch grandchild categories
watch(
    () => store.child_id,
    (newChildId) => {
        store.grandchild_id = null;
        store.fetchSubcategories(newChildId, store.grandchildCategories);
    }
);

// Handle category filter submission
function applyCategoryFilter() {
    form.category_id = store.grandchild_id || store.child_id || store.parent_id;

    form.get(route("home"), {
        data: {
            category_id: form.category_id,
        },
        onSuccess: () => {
            console.log("Filter applied");
        },
        onError: (errors) => {
            console.error("Error applying filter:", errors);
        },
    });
}

// Clear filter and reset selections
function clearFilter() {
    store.clearSelections();
    form.get(route("home"), {
        onSuccess: () => {
            console.log("Filter cleared");
        },
        onError: (errors) => {
            console.error("Error clearing filter:", errors);
        },
    });
}
</script>

<template>
    <aside
        class="fixed top-0 left-0 z-40 h-screen w-64 bg-white p-6 shadow-lg transition-transform duration-300 lg:translate-x-0"
        :class="{
            'translate-x-0': isOpen,
            '-translate-x-full': !isOpen,
        }"
    >
        <!-- Logo + Close Button -->
        <div class="flex items-center justify-between mb-8">
            <Link :href="route('home')">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>
            <button
                class="p-2 rounded-md hover:bg-gray-100 lg:hidden"
                @click="$emit('toggle')"
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
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Category Filter -->
        <nav class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-700">
                Filter by Category
            </h3>

            <!-- Parent Category -->
            <div>
                <label
                    for="parent_id"
                    class="block textsm font-medium text-gray-700"
                >
                    Category
                </label>
                <select
                    id="parent_id"
                    v-model="store.parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option :value="null">All Categories</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Child Category -->
            <div v-if="store.childCategories.length > 0">
                <label
                    for="child_id"
                    class="block text-sm font-medium text-gray-700"
                >
                    Subcategory
                </label>
                <select
                    id="child_id"
                    v-model="store.child_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option :value="null">All Subcategories</option>
                    <option
                        v-for="child in store.childCategories"
                        :key="child.id"
                        :value="child.id"
                    >
                        {{ child.name }}
                    </option>
                </select>
            </div>

            <!-- Grandchild Category -->
            <div v-if="store.grandchildCategories.length > 0">
                <label
                    for="grandchild_id"
                    class="block text-sm font-medium text-gray-700"
                >
                    Sub-Subcategory
                </label>
                <select
                    id="grandchild_id"
                    v-model="store.grandchild_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option :value="null">All Sub-Subcategories</option>
                    <option
                        v-for="grandchild in store.grandchildCategories"
                        :key="grandchild.id"
                        :value="grandchild.id"
                    >
                        {{ grandchild.name }}
                    </option>
                </select>
            </div>

            <!-- Apply Filter Button -->
            <div class="flex justify-end">
                <button
                    @click="applyCategoryFilter"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :disabled="form.processing"
                >
                    Apply Filter
                </button>
            </div>
            <div class="flex justify-end">
                <button
                    @click="clearFilter"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    :disabled="form.processing"
                >
                    Clear Filter
                </button>
            </div>
        </nav>
    </aside>
</template>
