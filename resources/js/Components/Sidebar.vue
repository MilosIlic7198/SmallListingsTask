<script setup>
import { router } from "@inertiajs/vue3";
import { watch } from "vue";
import { useStore } from "@/stores/Store";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    categories: {
        type: Array,
    },
});

const store = useStore();

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
    router.get(route("publicIndex"), {
        category_id: store.category_id,
        search: store.search,
    });
}

// Clear filter and reset selections
function clearFilter() {
    store.clearSelections();
    router.get(route("publicIndex"), {
        category_id: null,
        search: store.search,
    });
}
</script>

<template>
    <aside
        class="fixed top-0 left-0 z-40 h-screen w-64 bg-white p-6 pt-20 shadow-sm transition-transform duration-300 lg:translate-x-0"
        :class="{
            'translate-x-0': isOpen,
            '-translate-x-full': !isOpen,
        }"
    >
        <!-- Category Filter -->
        <nav class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-700">
                Filter by Category
            </h3>

            <!-- Parent Category -->
            <div>
                <label
                    for="parent_id"
                    class="block text-sm font-medium text-gray-700"
                >
                    Category
                </label>
                <select
                    id="parent_id"
                    v-model="store.parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Apply Filter
                </button>
            </div>
            <div class="flex justify-end">
                <button
                    @click="clearFilter"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Clear Filter
                </button>
            </div>
        </nav>
    </aside>
</template>
